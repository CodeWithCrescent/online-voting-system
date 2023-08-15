  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link nav-dashboard<?php echo (isset($_GET['page']) && ($_SESSION['login_type'] == 0 ? $_GET['page'] === 'dashboard' : $_GET['page'] === 'vote')) || ($_SERVER['SCRIPT_NAME'] === 'index.php') ? '' : ' collapsed'; ?>" href="index.php<?php echo $_SESSION['login_type'] == 0 ? '?page=dashboard' : '?page=vote'; ?>">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <?php if ($_SESSION['login_type'] == 0) { ?>
        <li class="nav-item">
          <a class="nav-link<?php echo isset($_GET['page']) && ($_GET['page'] === 'election_config' || $_GET['page'] === 'candidates' || $_GET['page'] === 'categories') ? '' : ' collapsed'; ?>" data-bs-target="#config-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-gear"></i>
            <span>Configuration</span>
            <i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="config-nav" class="nav-content collapse<?php echo isset($_GET['page']) && ($_GET['page'] === 'election_config' || $_GET['page'] === 'candidates' || $_GET['page'] === 'categories') ? ' show' : ''; ?>" data-bs-parent="#sidebar-nav">
            <li>
              <a href="index.php?page=election_config" class="<?php echo isset($_GET['page']) && ($_GET['page'] === 'election_config' || $_GET['page'] === 'candidates') ? ' active' : ''; ?>">
                <i class="bi bi-circle"></i><span>Elections</span>
              </a>
            </li>
            <li>
              <a href="index.php?page=categories" class="<?php echo isset($_GET['page']) && $_GET['page'] === 'categories' ? ' active' : ''; ?>">
                <i class="bi bi-circle"></i><span>Categories</span>
              </a>
            </li>
          </ul>
        </li><!-- End Configuration Page Nav -->

        <li class="nav-item">
          <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] === 'users' ? '' : ' collapsed'; ?>" href="index.php?page=users">
            <i class="bi bi-people"></i>
            <span>Users</span>
          </a>
        </li><!-- End Users Page Nav -->

        <li class="nav-item">
          <a class="nav-link<?php echo isset($_GET['page']) && ($_GET['page'] === 'vote' || $_GET['page'] === 'vote_details') ? '' : ' collapsed'; ?>" href="index.php?page=vote">
            <i class="bi bi-box2"></i>
            <span>Vote</span>
          </a>
        </li><!-- End Vote Page Nav -->
      <?php } ?>
      <li class="nav-item">
        <a class="nav-link<?php echo isset($_GET['page']) && $_GET['page'] === 'results' ? '' : ' collapsed'; ?>" href="index.php?page=results">
          <i class="bi bi-receipt"></i>
          <span>Results</span>
        </a>
      </li><!-- End Results Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->