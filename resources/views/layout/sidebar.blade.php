<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Dashboard</h4>
        </div>
        <div class="toggle-icon ms-auto">
            <i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Application</div>
            </a>
            <ul>
                <li>
                    <a href="{{route('authors')}}">
                        <i class="bx bx-right-arrow-alt"></i>Crud Author</a>
                </li>
                <li>
                    <a href="{{route('categories')}}">
                        <i class="bx bx-right-arrow-alt"></i>Crud Categories</a>
                </li>
                <li>
                    <a href="{{route('books')}}">
                        <i class="bx bx-right-arrow-alt"></i>Crud Books</a>
                </li>
            </ul>
        </li>
    </ul>
    <!--end navigation-->
</div>