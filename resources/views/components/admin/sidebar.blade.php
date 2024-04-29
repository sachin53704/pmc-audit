<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('admin/images/logo-sm.png') }}" alt="" height="22" />
            </span>
            <span class="logo-lg">
                <img src="{{ asset('admin/images/logo-dark.png') }}" alt="" height="17" />
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('admin/images/logo-sm.png') }}" alt="" height="22" />
            </span>
            <span class="logo-lg">
                <img src="{{ asset('admin/images/logo-light.png') }}" alt="" height="17" />
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title">
                    <span data-key="t-menu">Menu</span>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" >
                        <i class="ri-dashboard-2-line"></i>
                        <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>


                @canany(['fiscal_years.view', 'departments.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('fiscal_years.index') || request()->routeIs('departments.index') ? 'active' : '' }}" href="#sidebarLayouts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                            <i class="ri-layout-3-line"></i>
                            <span data-key="t-layouts">Masters</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarLayouts">
                            <ul class="nav nav-sm flex-column">
                                @can('fiscal_years.view')
                                    <li class="nav-item">
                                        <a href="{{ route('fiscal_years.index') }}" class="nav-link {{ request()->routeIs('fiscal_years.index') ? 'active' : '' }}" data-key="t-horizontal">Fiscal Year</a>
                                    </li>
                                @endcan
                                @can('departments.view')
                                    <li class="nav-item">
                                        <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.index') ? 'active' : '' }}" data-key="t-horizontal">Departments</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany


                @canany(['users.view', 'roles.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('users.index') || request()->routeIs('roles.index') ? 'active' : '' }}" href="#sidebarLayouts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                            <i class="bx bx-user-circle"></i>
                            <span data-key="t-layouts">User Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarLayouts">
                            <ul class="nav nav-sm flex-column">
                                @can('users.view')
                                    <li class="nav-item">
                                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" data-key="t-horizontal">Users</a>
                                    </li>
                                @endcan
                                @can('roles.view')
                                    <li class="nav-item">
                                        <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}" data-key="t-horizontal">Roles</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan


                @can('audit.view')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('audit.index') ? 'active' : '' }}" href="{{ route('audit.index') }}" >
                            <i class="ri-pages-line"></i>
                            <span data-key="t-dashboards">Upload Audit</span>
                        </a>
                    </li>
                @endcan


                @canany(['audit_list.pending', 'audit_list.approved', 'audit_list.rejected'])
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('audit-list.status', ['status' => 'pending']) ? 'active' : '' }}" href="#sidebarLayouts" data-bs-toggle="collapse" role="button">
                            <i class="ri-file-list-3-line"></i>
                            <span data-key="t-layouts">Audit List</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarLayouts">
                            <ul class="nav nav-sm flex-column">
                                @can('audit_list.pending')
                                    <li class="nav-item">
                                        <a href="{{ route('audit-list.status', ['status' => 'pending']) }}" class="nav-link {{ request()->is('audit/status/pending') ? 'active' : '' }}" data-key="t-horizontal">Pending Audit</a>
                                    </li>
                                @endcan
                                @can('audit_list.approved')
                                    <li class="nav-item">
                                        <a href="{{ route('audit-list.status', ['status' => 'approved']) }}" class="nav-link {{ request()->is('audit/status/approved') ? 'active' : '' }}" data-key="t-horizontal">Approved Audit</a>
                                    </li>
                                @endcan
                                @can('audit_list.rejected')
                                    <li class="nav-item">
                                        <a href="{{ route('audit-list.status', ['status' => 'rejected']) }}" class="nav-link {{ request()->is('audit/status/rejected') ? 'active' : '' }}" data-key="t-horizontal">Rejected Audit</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan


                @can('assigned_audit.view')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('assigned-audit.index') ? 'active' : '' }}" href="{{ route('assigned-audit.index') }}" >
                            <i class="ri-pass-valid-line"></i>
                            <span data-key="t-dashboards">Assigned Audit</span>
                        </a>
                    </li>
                @endcan


                @can('department_letter.view')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('department-letter.index') ? 'active' : '' }}" href="{{ route('department-letter.index') }}" >
                            <i class="ri-mail-download-line"></i>
                            <span data-key="t-dashboards">Department Letter</span>
                        </a>
                    </li>
                @endcan


                @can('objection.create')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('objection.create') ? 'active' : '' }}" href="{{ route('objection.create') }}" >
                            <i class="ri-auction-line"></i>
                            <span data-key="t-dashboards">Create Objection</span>
                        </a>
                    </li>
                @endcan


                @can('compliance.create')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('compliance.create') ? 'active' : '' }}" href="{{ route('compliance.create') }}" >
                            <i class="ri-file-list-3-line"></i>
                            <span data-key="t-dashboards">Create Compliance</span>
                        </a>
                    </li>
                @endcan


            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>


<div class="vertical-overlay"></div>
