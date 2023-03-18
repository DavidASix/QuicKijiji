
<?php
function currentActiveRoute($route) {
  if ($route === $_SERVER['REQUEST_URI']) {
    echo 'text-primary';
  }
}
?>

<nav class="navbar navbar-expand-md navbar-light bg-light m-3 px-3 rounded-4 shadow">
  <div class="container-fluid">

    <a class="navbar-brand" href="/classifieds">
      <div class=" d-flex align-items-center">
        <img src="/src/images/kijiji.svg" alt="Kijiji" class="me-3" height="40">
        <h2>Speed Tracker</h2>
      </div>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link text-start fs-4 px-3 <?php currentActiveRoute('/classifieds/')?>" href="/classifieds">
          <?php if(!empty($_SESSION['user_id'])) : ?>
            Listings
          <?php else : ?>
            Home
          <?php endif; ?>
        </a>

        <?php if(!empty($_SESSION['user_id'])) : ?>
          <a class="nav-link text-start fs-4 px-3 <?php currentActiveRoute('/profile/')?>" href="/profile">
            Profile
          </a>
          <a class="nav-link text-start fs-4 px-3" href=<?=baseUrl.'/src/server/logout.php'?>>
            Logout
          </a>
        <?php endif; ?>
      </div>
    </div>

  </div>
</nav>