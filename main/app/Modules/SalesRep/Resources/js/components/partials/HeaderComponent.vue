<template>
  <header>
    <div class="header-left">
      <!-- navigation-toggle  -->
      <a href class="header-btn side-nav-toggle">
        <span></span>
        <span></span>
        <span></span>
      </a>

      <!-- window full screen -->
      <a href id="fullScreen" class="header-btn hide-md">
        <i class="fa fa-expand"></i>
      </a>

      <!-- search -->
      <div class="form-group search-field flex a-i-center hide-xs">
        <div class="header-divider"></div>
        <label for="header-search">
          <i class="fa fa-search"></i>
        </label>
        <input type="text" class="form-control" id="header-search" placeholder="Search here..." />
      </div>
    </div>

    <div class="header-right">
      <!-- dropdown language -->
      <div class="dropdown hide-md dropdown-xs">
        <a href class="header-btn alert-active" data-toggle="dropdown">
          <i class="far fa-flag"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right text-center">
          <a href class="dropdown-item">TR</a>
          <a href class="dropdown-item active">EN</a>
          <a href class="dropdown-item">RU</a>
        </div>
      </div>
      <!-- dropdown language //. -->

      <!-- dropdown notification -->
      <div class="dropdown media-dropdown">
        <a href class="header-btn alert-active" data-toggle="dropdown">
          <i class="far fa-bell">
            <span class="alert-dot">
              <span class="badge badge-dot badge-sm badge-primary"></span>
            </span>
          </i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <div class="media-wrapper">
            <div class="media-item">
              <div class="item-thumbnail bg-success">
                <i class="far fa-user"></i>
              </div>
              <div class="item-content">
                <p>
                  <strong>New user registration</strong>
                  <small class="float-right">5 hours ago</small>
                </p>
              </div>
            </div>
            <div class="media-item">
              <div class="item-thumbnail bg-danger">
                <i class="fa fa-dollar-sign"></i>
              </div>
              <div class="item-content">
                <p>
                  <strong>$392 today sales earning.</strong>
                  <small class="float-right">1 day ago</small>
                </p>
              </div>
            </div>
            <div class="media-item">
              <div class="item-thumbnail bg-warning">
                <i class="fa fa-dollar-sign"></i>
              </div>
              <div class="item-content">
                <p>
                  <strong>Last Site Update</strong>
                  <small class="float-right">2 day ago</small>
                </p>
              </div>
            </div>
            <a href class="media-all-view">All View</a>
          </div>
        </div>
      </div>
      <!-- dropdown notification //. -->

      <!-- dropdown message -->
      <div class="dropdown media-dropdown hide-md">
        <a href class="header-btn" data-toggle="dropdown">
          <i class="far fa-envelope"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <div class="media-wrapper">
            <div class="media-item">
              <div class="item-thumbnail media-avatar-status">
                <span class="badge badge-dot badge-success"></span>
                <img src="/img/user-1.png" class="img-responsive" alt="..." />
              </div>
              <div class="item-content">
                <p>
                  <a href="app-inbox.html">
                    <strong>Application Error</strong>
                  </a>
                  <small class="float-right">12 hours ago</small>
                </p>
                <p class="small">There is an error in the sales application...</p>
              </div>
            </div>
            <div class="media-item">
              <div class="item-thumbnail media-avatar-status">
                <span class="badge badge-dot badge-danger"></span>
                <img src="/img/user-2.png" class="img-responsive" alt="..." />
              </div>
              <div class="item-content">
                <p>
                  <a href="app-inbox.html">
                    <strong>New Product</strong>
                  </a>
                  <small class="float-right">12 hours ago</small>
                </p>
                <p class="small">New product detail and pictures</p>
              </div>
            </div>
            <div class="media-item">
              <div class="item-thumbnail media-avatar-status">
                <span class="badge badge-dot badge-danger"></span>
                <img src="/img/user-3.png" class="img-responsive" alt="..." />
              </div>
              <div class="item-content">
                <p>
                  <a href="app-inbox.html">
                    <strong>Weekly Sales Report</strong>
                  </a>
                  <small class="float-right">1 day ago</small>
                </p>
                <p class="small">The weekly report can be found in the...</p>
              </div>
            </div>
            <a href="app-inbox.html" class="media-all-view">All View</a>
          </div>
        </div>
      </div>
      <!-- dropdown message //. -->

      <!-- dropdown user-->
      <div class="dropdown logged-user">
        <a href="#" class="header-btn" data-toggle="dropdown">
          <img src="/img/user-4.png" alt="..." />
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <a href="page-profile.html" class="dropdown-item">
            <i class="fa fa-user mr-10"></i> Profile
          </a>
          <a href="app-inbox.html" class="dropdown-item">
            <i class="far fa-envelope mr-10"></i> Inbox
          </a>
          <a href="user-profile.html" class="dropdown-item">
            <i class="fa fa-cog mr-10"></i> Settings
          </a>
          <a href="#" @click.prevent="$emit('logout-user')" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-10"></i> Log Out
          </a>
        </div>
      </div>
      <!-- dropdown user //. -->
    </div>
  </header>
</template>

<script>
  export default {};
</script>

<style lang="scss" scoped>
</style>
