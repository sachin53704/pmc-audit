<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="#" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('admin/images/logo-sm.png') }}" alt="" height="22" />
            </span>
            <span class="logo-lg">
                <img src="{{ asset('admin/images/logo-dark.png') }}" alt="" height="55" />
            </span>
        </a>
        <!-- Light Logo-->
        <a href="#" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('admin/images/logo-sm.png') }}" alt="" height="22" />
            </span>
            <span class="logo-lg">
                <img src="{{ asset('admin/images/logo-light.png') }}" alt="" height="55" />
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

                @if($canMenuVisible)
                    <li class="menu-title">
                        <span data-key="t-menu">Menu</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" >
                            <i class="ri-dashboard-2-line"></i>
                            <span data-key="t-dashboards">Dashboard</span>
                        </a>
                    </li>

                    @if(session()->get('LOGIN_TYPE') == 1)

                        @canany(['fiscal_years.view', 'departments.view'])
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('fiscal_years.index') || request()->routeIs('departments.index') || request()->routeIs('audit-para-category.index') || request()->routeIs('audit-type.index') || request()->routeIs('severity.index') || request()->routeIs('zone.index') ? 'active' : '' }}" href="#sidebarMasterLayouts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMasterLayouts">
                                    <i class="ri-layout-3-line"></i>
                                    <span data-key="t-layouts">Masters</span>
                                </a>
                                <div class="collapse menu-dropdown {{ request()->routeIs('fiscal_years.index') || request()->routeIs('departments.index') || request()->routeIs('audit-para-category.index') || request()->routeIs('audit-type.index') || request()->routeIs('severity.index') || request()->routeIs('zone.index') ? 'show' : '' }}" id="sidebarMasterLayouts">
                                    <ul class="nav nav-sm flex-column">
                                        @can('fiscal_years.view')
                                            <li class="nav-item">
                                                <a href="{{ route('fiscal_years.index') }}" class="nav-link {{ request()->routeIs('fiscal_years.index') ? 'active' : '' }}" data-key="t-horizontal">Financial Year</a>
                                            </li>
                                        @endcan
                                        @can('departments.view')
                                            <li class="nav-item">
                                                <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.index') ? 'active' : '' }}" data-key="t-horizontal">Departments</a>
                                            </li>
                                        @endcan
                                        
                                        @can('audit-para-category.index')
                                        <li class="nav-item">
                                            <a href="{{ route('audit-para-category.index') }}" class="nav-link {{ request()->routeIs('audit-para-category.index') ? 'active' : '' }}" data-key="t-horizontal">Audit Para Category</a>
                                        </li>
                                        @endcan
                                        
                                        @can('audit-type.index')
                                        <li class="nav-item">
                                            <a href="{{ route('audit-type.index') }}" class="nav-link {{ request()->routeIs('audit-type.index') ? 'active' : '' }}" data-key="t-horizontal">Audit Type</a>
                                        </li>
                                        @endcan
                                        
                                        @can('severity.index')
                                        <li class="nav-item">
                                            <a href="{{ route('severity.index') }}" class="nav-link {{ request()->routeIs('severity.index') ? 'active' : '' }}" data-key="t-horizontal">Severity</a>
                                        </li>
                                        @endcan
                                        
                                        @can('zone.index')
                                        <li class="nav-item">
                                            <a href="{{ route('zone.index') }}" class="nav-link {{ request()->routeIs('zone.index') ? 'active' : '' }}" data-key="t-horizontal">Zone</a>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcanany


                        @canany(['users.view', 'roles.view'])
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('users.index') || request()->routeIs('roles.index') ? 'active' : '' }}" href="#sidebarUserManagementLayouts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarUserManagementLayouts">
                                    <i class="bx bx-user-circle"></i>
                                    <span data-key="t-layouts">User Management</span>
                                </a>
                                <div class="collapse menu-dropdown {{ request()->routeIs('users.index') || request()->routeIs('roles.index') ? 'show' : '' }}" id="sidebarUserManagementLayouts">
                                    <ul class="nav nav-sm flex-column">
                                        @can('users.view')
                                            <li class="nav-item">
                                                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" data-key="t-horizontal">Users/Auditor</a>
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
                                    <span data-key="t-dashboards">Upload Pogramme Audit</span>
                                </a>
                            </li>
                        @endcan

                        @can('diary.index')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('diary.index') ? 'active' : '' }}" href="{{ route('diary.index') }}" >
                                    <i class="ri-pages-line"></i>
                                    <span data-key="t-dashboards">Diary</span>
                                </a>
                            </li>
                        @endcan


                        @canany(['audit_list.pending', 'audit_list.approved', 'audit_list.rejected'])
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('audit-list.status', ['status' => 'pending']) ? 'active' : '' }}" href="#sidebarProgrammeAuditListLayouts" data-bs-toggle="collapse" role="button">
                                    <i class="ri-file-list-3-line"></i>
                                    <span data-key="t-layouts">Programme Audit</span>
                                </a>
                                <div class="collapse menu-dropdown {{ request()->routeIs('audit-list.status', ['status' => 'pending']) ? 'show' : '' }}" id="sidebarProgrammeAuditListLayouts">
                                    <ul class="nav nav-sm flex-column">
                                        @can('audit_list.pending')
                                            <li class="nav-item">
                                                <a href="{{ route('audit-list.status', ['status' => 'pending']) }}" class="nav-link {{ request()->is('audit/status/pending') ? 'active' : '' }}" data-key="t-horizontal">Pending Programme Audit</a>
                                            </li>
                                        @endcan
                                        @can('audit_list.approved')
                                            <li class="nav-item">
                                                <a href="{{ route('audit-list.status', ['status' => 'approved']) }}" class="nav-link {{ request()->is('audit/status/approved') ? 'active' : '' }}" data-key="t-horizontal">Approved Programme Audit</a>
                                            </li>
                                        @endcan
                                        @can('audit_list.rejected')
                                            <li class="nav-item">
                                                <a href="{{ route('audit-list.status', ['status' => 'rejected']) }}" class="nav-link {{ request()->is('audit/status/rejected') ? 'active' : '' }}" data-key="t-horizontal">Rejected Programme Audit</a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcan


                        @can('audit_list.assign')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('assign-auditor') ? 'active' : '' }}" href="{{ route('assign-auditor') }}" >
                                    <i class="ri-pass-valid-line"></i>
                                    <span data-key="t-dashboards">Assign Auditor</span>
                                </a>
                            </li>
                        @endcan


                        @can('assigned_audit.view')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('assigned-audit.index') ? 'active' : '' }}" href="{{ route('assigned-audit.index') }}" >
                                    <i class="ri-pass-valid-line"></i>
                                    <span data-key="t-dashboards">Assigned Programme Audit</span>
                                </a>
                            </li>
                        @endcan


                        @can('objection.create')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('objection.create') ? 'active' : '' }}" href="{{ route('objection.create') }}" >
                                    <i class="ri-auction-line"></i>
                                    <span data-key="t-dashboards">HMM</span>
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


                        @can('compliance.create')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('compliance.create') ? 'active' : '' }}" href="{{ route('compliance.create') }}" >
                                    <i class="ri-file-list-3-line"></i>
                                    <span data-key="t-dashboards">HMM Questions</span>
                                </a>
                            </li>
                        @endcan


                        @can('answered-questions.view')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('answered-questions') ? 'active' : '' }}" href="{{ route('answered-questions') }}" >
                                    <i class="ri-pass-valid-line"></i>
                                    <span data-key="t-dashboards">Answered Questions</span>
                                </a>
                            </li>
                        @endcan


                        @can('draft-review.view')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('draft-review') ? 'active' : '' }}" href="{{ route('draft-review') }}" >
                                    <i class="ri-draft-line"></i>
                                    <span data-key="t-dashboards">Draft Review</span>
                                </a>
                            </li>
                        @endcan



                        @canany(['report.final-report'])
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('final-report') || request()->routeIs('departments.index') || request()->routeIs('complience-answer-report') ? 'active' : '' }}" href="#sidebarLayouts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLayouts">
                                    <i class="ri-layout-3-line"></i>
                                    <span data-key="t-layouts">Report</span>
                                </a>
                                <div class="collapse menu-dropdown {{ request()->routeIs('final-report') || request()->routeIs('departments.index') || request()->routeIs('complience-answer-report') ? 'show' : '' }}" id="sidebarLayouts">
                                    <ul class="nav nav-sm flex-column">
                                        @can('report.final-report')
                                            <li class="nav-item">
                                                <a href="{{ route('final-report') }}" class="nav-link {{ request()->routeIs('final-report') ? 'active' : '' }}" data-key="t-horizontal">Final Report</a>
                                            </li>
                                        @endcan
                                        @can('report.para-audit')
                                        {{-- hmm not get objection answer --}}
                                            <li class="nav-item">
                                                <a href="{{ route('para-audit-report') }}" class="nav-link {{ request()->routeIs('para-audit-report') ? 'active' : '' }}" data-key="t-horizontal">Para Audit Report</a>
                                            </li>
                                        @endcan
                                        @can('report.complience-answer')
                                        {{-- hmm get objection answer --}}
                                            <li class="nav-item">
                                                <a href="{{ route('complience-answer-report') }}" class="nav-link {{ request()->routeIs('complience-answer-report') ? 'active' : '' }}" data-key="t-horizontal">Complience Answer Report</a>
                                            </li>
                                        @endcan
                                        @can('report.department')
                                        {{-- hmm get objection answer --}}
                                            <li class="nav-item">
                                                <a href="{{ route('department-program-audit') }}" class="nav-link {{ request()->routeIs('cdepartment-program-audit') ? 'active' : '' }}" data-key="t-horizontal">Department Program Audit Report</a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcanany

                    @else

                        @if(auth()->user()->department_id == 1 )
                            @can('receipt.view')
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ request()->routeIs('receipts.index') ? 'active' : '' }}" href="{{ route('receipts.index') }}" >
                                        <i class="ri-pages-line"></i>
                                        <span data-key="t-dashboards">Upload Receipt</span>
                                    </a>
                                </li>
                            @endcan

                            @can('payment-receipt.view')
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ request()->routeIs('payment-receipts.index') ? 'active' : '' }}" href="{{ route('payment-receipts.index') }}" >
                                        <i class="ri-pages-line"></i>
                                        <span data-key="t-dashboards">Upload Payment Receipt</span>
                                    </a>
                                </li>
                            @endcan
                        @endif


                        @can('receipt.pending-list')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('receipts.pending-list') ? 'active' : '' }}" href="{{ route('receipts.pending-list') }}" >
                                    <i class="ri-pages-line"></i>
                                    <span data-key="t-dashboards">Pending Receipts</span>
                                </a>
                            </li>
                        @endcan

                        @can('receipt.approve-list')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('receipts.approved-list') ? 'active' : '' }}" href="{{ route('receipts.approved-list') }}" >
                                    <i class="ri-pages-line"></i>
                                    <span data-key="t-dashboards">Approved Receipts</span>
                                </a>
                            </li>
                        @endcan

                        @can('receipt.reject-list')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('receipts.rejected-list') ? 'active' : '' }}" href="{{ route('receipts.rejected-list') }}" >
                                    <i class="ri-pages-line"></i>
                                    <span data-key="t-dashboards">Rejected Receipts</span>
                                </a>
                            </li>
                        @endcan



                        @can('payment-receipt.pending-list')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('payment-receipts.pending-list') ? 'active' : '' }}" href="{{ route('payment-receipts.pending-list') }}" >
                                    <i class="ri-pages-line"></i>
                                    <span data-key="t-dashboards">Pending Payment Receipts</span>
                                </a>
                            </li>
                        @endcan

                        @can('payment-receipt.approve-list')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('payment-receipts.approved-list') ? 'active' : '' }}" href="{{ route('payment-receipts.approved-list') }}" >
                                    <i class="ri-pages-line"></i>
                                    <span data-key="t-dashboards">Approved Payment Receipts</span>
                                </a>
                            </li>
                        @endcan

                        @can('payment-receipt.reject-list')
                            <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('payment-receipts.rejected-list') ? 'active' : '' }}" href="{{ route('payment-receipts.rejected-list') }}" >
                                    <i class="ri-pages-line"></i>
                                    <span data-key="t-dashboards">Rejected Payment Receipts</span>
                                </a>
                            </li>
                        @endcan
                    @endif

                @endif


            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>


<div class="vertical-overlay"></div>
