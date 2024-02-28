<div class="leftside-menu">

    <!-- Brand Logo Light -->
    <a href="index.html" class="logo logo-light">
                    <span class="logo-lg">
                        রিলায়েন্স শ্রমজীবি সমবায় সমিতি লিঃ
                    </span>
        <span class="logo-sm">
                        রিলায়েন্স
                    </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="index.html" class="logo logo-dark">
                    <span class="logo-lg">
                        রিলায়েন্স শ্রমজীবি সমবায় সমিতি লিঃ
                    </span>
        <span class="logo-sm">
                        রিলায়েন্স
                    </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <!-- Full Sidebar Menu Close Button -->
    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!-- Leftbar User -->
        <div class="leftbar-user">
            <a href="pages-profile.html">
                <img src="{{asset('assets/images/users/avatar-1.jpg')}}" alt="user-image" height="42" class="rounded-circle shadow-sm">
                <span class="leftbar-user-name mt-2">Dominic Keller</span>
            </a>
        </div>

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title">Navigation</li>

            <li class="side-nav-item">
                <a href="{{ url("/") }}" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span> ড্যাশবোর্ড </span>
                </a>
            </li>
<li class="side-nav-item">
                <a href="{{ url("new-account") }}" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span> নতুন সঞ্চয় হিসাব </span>
                </a>
            </li>
            <li class="side-nav-title">Modules</li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCollections" aria-expanded="false" aria-controls="sidebarCrm" class="side-nav-link">
                    <i class="uil uil-tachometer-fast"></i>

                    <span class="menu-arrow"></span>
                    <span> সঞ্চয় আদায়/ঋণ ফেরত  </span>
                </a>
                <div class="collapse" id="sidebarCollections">
                    <ul class="side-nav-second-level">
                        @can('daily-collection')
                        <li>
                            <a href="{{ route('daily-collections.index') }}">দৈনিক সঞ্চয় আদায়/উত্তোলন</a>
                        </li>
                        @endcan
                        @can('monthly-collection')
                        <li>
                            <a href="{{ route('monthly-collections.index') }}">মাসিক সঞ্চয়/লভ্যাংশ আদায়</a>
                        </li>
                            @endcan
                    </ul>
                </div>
            </li>
            @can('menu')
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarUsers" aria-expanded="false" aria-controls="sidebarCrm" class="side-nav-link">
                    <i class="uil uil-tachometer-fast"></i>
                    <span class="menu-arrow"></span>
                    <span> সদস্য ব্যবস্থাপনা </span>
                </a>
                <div class="collapse" id="sidebarUsers">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('members.create') }}"> নতুন সদস্য যোগ</a>
                        </li>
                        <li>
                             <a href="{{ route('members.index') }}"> সকল সদস্য</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarDaily" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                    <i class="uil-store"></i>
                    <span> দৈনিক সঞ্চয় </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarDaily">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('daily-savings.create') }}">নতুন সঞ্চয় হিসাব </a>
                        </li>
                        <li>
                            <a href="{{ route('daily-loans.create') }}">নতুন ঋণ প্রদান </a>
                        </li>
                        <li>
                            <a href="{{ route('daily-savings.index') }}">সঞ্চয় তালিকা</a>
                        </li>
                        <li>
                            <a href="{{ url('daily-profits') }}">দৈনিক সঞ্চয় লভ্যাংশ</a>
                        </li>
                        <li>
                            <a href="{{ route('daily-loans.index') }}">দৈনিক ঋণ</a>
                        </li>

                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarEmail" aria-expanded="false" aria-controls="sidebarEmail" class="side-nav-link">
                    <i class="uil-envelope"></i>
                    <span> মাসিক সঞ্চয় </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarEmail">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('monthly-savings.create') }}">নতুন সঞ্চয় হিসাব</a>
                        </li>
                        <li>
                            <a href="{{ route('monthly-loans.create') }}">নতুন ঋণ প্রদান</a>
                        </li>
                        <li>
                            <a href="{{ route('monthly-savings.index') }}">সঞ্চয় তালিকা</a>
                        </li>
                        <li>
                            <a href="{{ url('monthly-profits') }}">মাসিক সঞ্চয় লভ্যাংশ</a>
                        </li>
                        <li>
                            <a href="{{ route('monthly-loans.index') }}">মাসিক ঋণ</a>
                        </li>

                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarProjects" aria-expanded="false" aria-controls="sidebarProjects" class="side-nav-link">
                    <i class="uil-briefcase"></i>
                    <span> কর্মী ব্যবস্থাপনা </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarProjects">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('employees.index') }}">কর্মীদের তালিকা</a>
                        </li>
                        <li>
                            <a href="{{ route('attendance.index') }}">কর্মীদের উপস্থিতি</a>
                        </li>
                        <li>
                            <a href="{{ route('payrolls.index') }}">কর্মীদের বেতন</a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarFdr" aria-expanded="false" aria-controls="sidebarTasks" class="side-nav-link">
                    <i class="uil-clipboard-alt"></i>
                    <span> FDR (এফ.ডি.আর) </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarFdr">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ url('fdr/create') }}">নতুন FDR </a>
                        </li>
                        <li>
                            <a href="{{ url('fdr') }}">FDR (এফ.ডি.আর) </a>
                        </li>
                        <li>
                            <a href="{{ url('fdr-deposits') }}">FDR জমা </a>
                        </li>
                        <li>
                            <a href="{{ url('fdr-withdraws') }}">FDR উত্তোলন </a>
                        </li>
                        <li>
                            <a href="{{ url('profit-collections') }}">মুনাফা উত্তোলন</a>
                        </li>

                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#income-expense" aria-expanded="false" aria-controls="sidebarTasks" class="side-nav-link">
                    <i class="uil-clipboard-alt"></i>
                    <span> আয়/ব্যয় </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="income-expense">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ url('income-categories') }}">আয় ক্যাটেগরি </a>
                        </li>
                        <li>
                            <a href="{{ url('expense-categories') }}">ব্যয় ক্যাটেগরি</a>
                        </li>
                        <li>
                            <a href="{{ url('incomes') }}">সকল আয়</a>
                        </li>
                        <li>
                            <a href="{{ url('expenses') }}">সকল ব্যয়</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a href="apps-file-manager.html" class="side-nav-link">
                    <i class="uil-folder-plus"></i>
                    <span> SMS (এস এম এস)</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPages" aria-expanded="false" aria-controls="sidebarPages" class="side-nav-link">
                    <i class="uil-copy-alt"></i>
                    <span> রিপোর্ট </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPages">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ url('daily-savings-report') }}">দৈনিক সঞ্চয়</a>
                        </li>
                        <li>
                            <a href="{{ url('daily-loan-report') }}">দৈনিক ঋণ</a>
                        </li>
                        <li>
                            <a href="{{ url('monthly-savings-report') }}">মাসিক সঞ্চয় </a>
                        </li>
                        <li>
                            <a href="{{ url('monthly-loan-report') }}">মাসিক ঋণ</a>
                        </li>
                        <li>
                            <a href="{{ url('fdr-deposit-report') }}">FDR জমা</a>
                        </li>
                        <li>
                            <a href="{{ url('fdr-withdraw-report') }}">FDR উত্তোলন</a>
                        </li>
                        <li>
                            <a href="{{ url('profit-collection-report') }}">মুনাফা উত্তোলন</a>
                        </li>
                        <li>
                            <a href="{{ url('daily-report') }}">ক্যাশবুক</a>
                        </li>
                        <li>
                            <a href="{{ url('transaction-summary-report') }}">আয়-ব্যয় রিপোর্ট</a>
                        </li>

                    </ul>
                </div>
            </li>
            @endcan
        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
