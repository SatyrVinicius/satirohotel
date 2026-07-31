<?php 
@session_start();
$_SESSION['pagina'] = 'quartos';
require_once("cabecalho.php");

$id = @$_GET['id'];

 ?>  


<?php
@session_start();
$_SESSION['pagina'] = 'quartos';
require_once("cabecalho.php");

$id = (int) (@$_GET['id'] ?? 0);

// Base vinda do cabecalho
$base = rtrim($url_sistema_cab ?? '/', '/');
$pathQuartos = '/sistema/painel/images/quartos/';
$pathFotosQuarto = '/sistema/painel/images/fotos_quartos/';

/* ========= Categoria ========= */
$stmt = $pdo->prepare("
  SELECT id, nome, descricao, especificacoes, foto, valor
    FROM categorias_quartos
   WHERE id = :id
");
$stmt->execute([':id' => $id]);
$cat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cat) {
  echo '<div class="container my-5"><div class="alert alert-danger">Categoria não encontrada.</div></div>';
  require_once("rodape.php"); exit;
}

$nomeCat  = trim($cat['nome'] ?? '');
$descCat  = trim($cat['descricao'] ?? '');
$valor    = (float)($cat['valor'] ?? 0);
$valorF   = 'R$ ' . number_format($valor, 2, ',', '.');
$fotoCapa = trim($cat['foto'] ?? ''); // arquivo na pasta "quartos"

// URL da imagem principal do HERO (uma só)
$heroSrc = !empty($fotoCapa)
  ? $base . $pathQuartos . ltrim($fotoCapa, '/')
  : ''; // se vazio, aparece placeholder

/* ========= Quartos da categoria ========= */
$sqlQs = $pdo->prepare("SELECT numero FROM quartos WHERE tipo = :id AND ativo = 'Sim' ORDER BY numero ASC");
$sqlQs->execute([':id' => $id]);
$nums = $sqlQs->fetchAll(PDO::FETCH_COLUMN);

/* ========= Especificações ========= */
$sqlEsp = $pdo->prepare("SELECT texto FROM especificacoes_quartos WHERE cat_quartos = :id ORDER BY id ASC");
$sqlEsp->execute([':id' => $id]);
$especs = $sqlEsp->fetchAll(PDO::FETCH_COLUMN);

/* ========= Galeria (slideshow abaixo) ========= */
/* ========= Galeria (slideshow abaixo) =========
   ATENÇÃO: aqui "quarto" guarda o ID da CATEGORIA
*/
$sqlGal = $pdo->prepare("
  SELECT DISTINCT fq.foto
  FROM fotos_quartos fq
  WHERE fq.quarto = :cat           -- aqui vai o id da categoria
  ORDER BY fq.id DESC
");
$sqlGal->execute([':cat' => $id]);
$gal = $sqlGal->fetchAll(PDO::FETCH_COLUMN);

// Pastas candidatas (ordem de preferência)
$CANDIDATES_DIRS = [
  '/sistema/painel/images/fotos_quartos/',
  '/sistema/painel/images/quartos/',
  '/sistema/painel/images/galeria/',
];

// Base FS do projeto (…/hotel/)
$APP_ROOT = rtrim(str_replace('\\','/', realpath(__DIR__)), '/') . '/';

// Resolve URL a partir de um nome de arquivo
function resolve_img_url($filename, $dirs, $baseUrl, $appRoot){
  $name = basename(trim((string)$filename));
  if ($name === '') return null;

  foreach ($dirs as $relDir){
    $abs = $appRoot . ltrim($relDir,'/') . $name;     // ex.: C:/xampp/htdocs/hotel/sistema/...
    if (is_file($abs)) {
      return rtrim($baseUrl,'/') . $relDir . $name;   // ex.: http://localhost/hotel/sistema/...
    }
  }
  return null;
}

$slides = [];
foreach ($gal as $arq){
  if ($url = resolve_img_url($arq, $CANDIDATES_DIRS, $base, $APP_ROOT)) {
    $slides[] = $url;
  }
}

// Fallback: usa a capa da categoria se não houver fotos específicas
if (empty($slides) && !empty($fotoCapa)) {
  $slides[] = rtrim($base,'/') . '/sistema/painel/images/quartos/' . ltrim($fotoCapa,'/');
}

$slides = array_values(array_unique(array_filter($slides)));

?>

<!-- HERO COM IMAGEM ÚNICA DA CATEGORIA -->
<section class="hero-unique">
  <div class="hero-bg">
    <?php if ($heroSrc): ?>
      <img src="<?= htmlspecialchars($heroSrc) ?>" alt="Imagem do quarto" class="hero-img">
    <?php else: ?>
      <div class="hero-placeholder"></div>
    <?php endif; ?>
    <div class="hero-overlay"></div>
  </div>

  <div class="container hero-content">
    <div class="row">
      <div class="col-12 col-lg-7">
        <h1 class="hero-title"><?= htmlspecialchars($nomeCat) ?></h1>
        <div class="hero-price">
          <span class="price"><?= $valorF ?></span>
          <span class="per">/ diária</span>
        </div>
        <?php if ($descCat): ?>
          <p class="hero-desc"></p>
        <?php endif; ?>
        <a href="<?php echo $url_sistema_cab ?>" class="btn btn-primary btn-sm px-4">
          Ver disponibilidade & Reservar
        </a>
      </div>
    </div>
  </div>
</section>


<?php
// normaliza quebras vindo com <br> no texto e evita HTML bruto
$descNorm = str_replace(['<br/>','<br />','<br>'], "\n", $descCat ?? '');
$descSafe = nl2br(htmlspecialchars($descNorm, ENT_QUOTES, 'UTF-8'));
?>
<section class="container section-details improved-details">
  <div class="details-grid">
    <article class="details-card">
      <h2 class="details-title">Descrição</h2>

      <div class="prose description" id="descricaoTexto">
        <?= $descSafe ?: '<span class="text-muted">Sem descrição cadastrada para esta categoria.</span>' ?>
      </div>

      <!-- Botão ver mais só aparece se o texto for grande (JS cuida disso) -->
      <button class="btn btn-outline-secondary btn-sm mt-3 d-none" id="btnToggleDesc" type="button">
        Ver mais
      </button>
    </article>

    <aside class="details-card">
      <?php if (!empty($especs)): ?>
        <h3 class="details-subtitle">Especificações</h3>
        <ul class="spec-list clean-list">
          <?php foreach ($especs as $t): ?>
            <li><span><?= htmlspecialchars($t) ?></span></li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <h3 class="details-subtitle">Especificações</h3>
        <div class="text-muted">Nenhuma especificação cadastrada.</div>
      <?php endif; ?>
    </aside>
  </div>
</section>


<!-- GALERIA / SLIDESHOW (galeria_site) -->
<section class="container section-gallery">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 m-0">Galeria de Fotos</h2>
    <?php if (!empty($slides) && count($slides) > 1): ?>
      <div class="small text-muted"><?= count($slides) ?> imagens</div>
    <?php endif; ?>
  </div>

  <?php if (!empty($slides)): ?>
    <!-- Grade de thumbs -->
   <div class="gallery-grid">
  <?php foreach ($slides as $i => $src): ?>
    <button
  class="gallery-item"
  type="button"
  data-index="<?= $i ?>"
  aria-label="Abrir imagem <?= $i+1 ?> em tela cheia">
  <img loading="lazy" src="<?= htmlspecialchars($src) ?>" alt="Foto <?= $i+1 ?>" class="gallery-thumb">
</button>
  <?php endforeach; ?>
</div>


    <!-- Lightbox (Modal + Carousel) -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-black">
          <div class="modal-header border-0">
            <h5 class="modal-title text-white">Galeria</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body p-0">
            <div id="lightboxCarousel" class="carousel slide h-100" data-bs-ride="false">
              <div class="carousel-inner h-100">
                <?php foreach ($slides as $i => $src): ?>
                  <div class="carousel-item h-100 <?= $i===0?'active':'' ?>">
                    <div class="lightbox-img-wrap">
                      <img src="<?= htmlspecialchars($src) ?>" class="lightbox-img" alt="Imagem <?= $i+1 ?>">
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>

              <?php if (count($slides) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Próxima</span>
                </button>

                <div class="carousel-indicators mb-4">
                  <?php foreach ($slides as $i => $_): ?>
                    <button type="button" data-bs-target="#lightboxCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i===0?'active':'' ?>" aria-label="Slide <?= $i+1 ?>"></button>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-light border">Nenhuma imagem na galeria.</div>
  <?php endif; ?>
</section>


<style>
/* ======= HERO ÚNICO ======= */
.hero-unique { position: relative; min-height: min(86vh, 860px); }
.hero-bg { position: absolute; inset: 0; }
.hero-img { width: 100%; height: 100%; object-fit: cover; filter: brightness(.92); }
.hero-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg,#f3f4f6,#e5e7eb); }
.hero-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,.35) 0%, rgba(0,0,0,.45) 40%, rgba(0,0,0,.2) 100%); }
.hero-content { position: relative; z-index: 1; padding-top: clamp(80px, 14vh, 160px); padding-bottom: clamp(24px, 6vh, 64px); color: #fff; }
.hero-title { font-size: clamp(28px, 4.8vw, 52px); font-weight: 800; margin-bottom: .35rem; }
.hero-price .price { font-size: clamp(22px, 3.2vw, 36px); font-weight: 700; }
.hero-price .per { font-size: clamp(14px, 1.6vw, 16px); margin-left: .25rem; opacity: .9; }
.hero-desc { max-width: 820px; font-size: clamp(14px, 1.6vw, 16px); opacity: .95; margin: .6rem 0 1rem; }

/* ======= SEÇÕES ======= */
.section-details { padding: clamp(28px, 4vw, 56px) 0; }
.section-gallery { padding: clamp(16px, 3vw, 40px) 0 60px; }

/* especificações em colunas */
.spec-list { columns: 2; column-gap: 2rem; padding-left: 1rem; }
.spec-list li { break-inside: avoid; margin-bottom: .45rem; }
@media (max-width: 768px){ .spec-list { columns: 1; } }

/* indicadores da galeria */
#galeriaQuarto .carousel-indicators [data-bs-target]{
  width: 10px; height: 10px; border-radius: 50%;
}


/* ===== Área de detalhes melhorada ===== */
.improved-details { padding: clamp(20px, 3vw, 48px) 0; }

.details-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: clamp(16px, 2.4vw, 28px);
}
@media (min-width: 992px) {
  .details-grid { grid-template-columns: 1.5fr 1fr; }
}

.details-card {
  background: #fff;
  border: 1px solid #eceff3;
  border-radius: 16px;
  padding: clamp(16px, 2.2vw, 28px);
  box-shadow: 0 6px 18px rgba(0,0,0,.04);
}

.details-title {
  font-size: clamp(20px, 2.8vw, 28px);
  font-weight: 800;
  margin: 0 0 .65rem 0;
}
.details-subtitle {
  font-size: clamp(16px, 2.1vw, 20px);
  font-weight: 700;
  margin: 0 0 .75rem 0;
}

/* Tipografia do texto longo */
.prose {
  font-size: clamp(14px, 1.7vw, 16px);
  line-height: 1.7;
  color: #374151;
  max-width: 72ch;           /* linha confortável */
  word-wrap: break-word;
}
.prose p { margin: 0 0 .85rem 0; }

/* Estado colapsado para mobile (ativado via JS) */
.prose.is-collapsed {
  max-height: 12.5rem;       /* ~200px */
  overflow: hidden;
  position: relative;
}
.prose.is-collapsed::after { /* fade no rodapé */
  content: "";
  position: absolute;
  left: 0; right: 0; bottom: 0;
  height: 3rem;
  background: linear-gradient(transparent, #fff);
}

/* Lista de especificações com bullets custom */
.clean-list { list-style: none; margin: 0; padding: 0; }
.spec-list li {
  display: flex; align-items: flex-start;
  gap: .6rem;
  padding: .4rem 0;
  border-bottom: 1px dashed #eef2f6;
}
.spec-list li:last-child { border-bottom: 0; }
.spec-list li::before {
  content: "";
  flex: 0 0 10px;
  height: 10px;
  margin-top: .45rem;
  border-radius: 50%;
  background: radial-gradient(circle at 30% 30%, #2c7be5, #1e5bb8);
  box-shadow: 0 0 0 3px rgba(44,123,229,.12);
}

/* Botão ver mais */
#btnToggleDesc {
  border-radius: 999px;
  padding: .35rem .9rem;
}

/* Ajustes finos */
.text-muted { color: #6b7280 !important; }







/* ===== Galeria (grade) ===== */
.gallery-grid{
  display:grid;
  gap: clamp(10px, 1.6vw, 16px);
  grid-template-columns: 1fr;
}
@media (min-width: 576px){ .gallery-grid{ grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 992px){ .gallery-grid{ grid-template-columns: repeat(3, 1fr); } }

.gallery-item{
  border:0; padding:0; margin:0; background:none; cursor:pointer;
  border-radius: 14px; overflow:hidden;
  box-shadow: 0 8px 20px rgba(0,0,0,.06);
  transition: transform .2s ease, box-shadow .2s ease;
}
.gallery-item:focus-visible{ outline: 3px solid #2c7be5; outline-offset: 2px; }
.gallery-item:hover{ transform: translateY(-2px); box-shadow:0 10px 24px rgba(0,0,0,.10); }

.gallery-thumb{
  width:100%; height:100%;
  display:block; object-fit: cover;
  aspect-ratio: 16/10; /* recorte elegante */
}

/* ===== Lightbox ===== */
.lightbox-img-wrap{
  position:relative; width:100%; height:100%;
  display:flex; align-items:center; justify-content:center;
  background: #000;
}
.lightbox-img{
  max-width: 96vw; max-height: 86vh;
  object-fit: contain; /* não corta no lightbox */
  filter: drop-shadow(0 10px 30px rgba(0,0,0,.45));
}

/* indicadores menores */
#lightboxCarousel .carousel-indicators [data-bs-target]{
  width:10px; height:10px; border-radius:50%;
}


/* === Lightbox em tela cheia e sem margens === */
#lightboxModal .modal-dialog{
  max-width: 100vw !important;
  width: 100vw !important;
  height: 100vh !important;
  margin: 0 !important;           /* remove as margens laterais */
}

#lightboxModal .modal-content{
  height: 100vh !important;
  border: 0;
  border-radius: 0;
  background: rgba(0,0,0,.95);    /* fundo escuro contínuo */
}

#lightboxModal .modal-body{
  padding: 0 !important;
}

/* Área da imagem ocupa toda a altura e centraliza */
#lightboxModal .lightbox-img-wrap{
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: transparent;
}

/* A imagem se ajusta (retrato ou paisagem) sem cortar */
#lightboxModal .lightbox-img{
  max-width: 96vw;
  max-height: 92vh;
  width: auto;
  height: auto;
  object-fit: contain;             /* garante que nada seja cortado */
}

/* Controles mais visíveis sobre fundo escuro */
#lightboxCarousel .carousel-control-prev,
#lightboxCarousel .carousel-control-next{
  filter: drop-shadow(0 2px 6px rgba(0,0,0,.6));
  opacity: .9;
}

#lightboxCarousel .carousel-indicators [data-bs-target]{
  width: 10px; height: 10px; border-radius: 50%;
}


</style>


<script>
(() => {
  const $desc = document.getElementById('descricaoTexto');
  const $btn  = document.getElementById('btnToggleDesc');
  if (!$desc || !$btn) return;

  // Se o texto for grande, ativa modo colapsado no mobile e mostra botão
  const bigEnough = $desc.innerText.trim().length > 420;  // limiar simples
  const isMobile  = () => window.matchMedia('(max-width: 991px)').matches;

  function applyState() {
    if (bigEnough && isMobile()) {
      $desc.classList.add('is-collapsed');
      $btn.classList.remove('d-none');
      $btn.innerText = 'Ver mais';
    } else {
      $desc.classList.remove('is-collapsed');
      $btn.classList.add('d-none');
    }
  }

  $btn.addEventListener('click', () => {
    const collapsed = $desc.classList.toggle('is-collapsed');
    $btn.innerText = collapsed ? 'Ver mais' : 'Ver menos';
  });

  applyState();
  window.addEventListener('resize', applyState, { passive: true });
})();
</script>



<script>
(() => {
  const modalEl    = document.getElementById('lightboxModal');
  const carouselEl = document.getElementById('lightboxCarousel');
  if (!modalEl || !carouselEl) return;

  // Instância única do Carousel
  const carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl, {
    interval: false,
    ride: false,
    wrap: true,
    touch: true,
    keyboard: true
  });

  function setActiveSlide(idx){
    const items = carouselEl.querySelectorAll('.carousel-item');
    const dots  = carouselEl.querySelectorAll('.carousel-indicators [data-bs-slide-to]');

    // sanity
    if (!items.length) return;
    idx = Math.max(0, Math.min(items.length - 1, Number(idx) || 0));

    // limpa "active" de tudo
    items.forEach(el => el.classList.remove('active'));
    dots.forEach(el => { el.classList.remove('active'); el.removeAttribute('aria-current'); });

    // ativa o desejado
    items[idx].classList.add('active');
    if (dots[idx]) { dots[idx].classList.add('active'); dots[idx].setAttribute('aria-current', 'true'); }

    // sincroniza com a instância
    carousel.to(idx);
  }

  // Clique na thumb: define slide e só depois abre o modal
  document.querySelectorAll('.gallery-item').forEach(btn => {
    btn.addEventListener('click', () => {
      const idx = Number(btn.dataset.index || 0);
      setActiveSlide(idx);

      const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    });
  });

  // Garantia extra: se por qualquer motivo abrir já visível, re-sincroniza
  modalEl.addEventListener('shown.bs.modal', () => {
    const current = carouselEl.querySelector('.carousel-item.active');
    const index = Array.from(carouselEl.querySelectorAll('.carousel-item')).indexOf(current);
    if (index >= 0) carousel.to(index);
  });
})();
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const modalEl    = document.getElementById('lightboxModal');
  const carouselEl = document.getElementById('lightboxCarousel');
  if (!modalEl || !carouselEl) return;

  const carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl, {
    interval: false,
    ride: false,
    wrap: true,
    touch: true,
    keyboard: true
  });

  // Clique em cada thumb
  document.querySelectorAll('.gallery-item').forEach((btn, i) => {
    btn.addEventListener('click', () => {
      // ativa o slide certo
      carousel.to(i);
      // abre o modal
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    });
  });
});
</script>



<?php require_once("rodape.php"); ?>

