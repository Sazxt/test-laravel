<!doctype html>
<html lang="en">
    @include('layout.head')
    <body>
        <!--wrapper-->
        <div class="wrapper">
            <!--sidebar wrapper -->
            @include("layout.sidebar")
            <!--end sidebar wrapper -->
            <!--start header -->
            @include("layout.header")
            <!--end header -->
            <!--start page wrapper -->
            <div class="page-wrapper">
                <div class="page-content">
                    @yield("content")
                </div>
            </div>
            <!--end page wrapper -->
            <!--start overlay-->
            <div class="overlay toggle-icon"></div>
            <!--end overlay-->
            <!--Start Back To Top Button-->
            <a href="javaScript:;" class="back-to-top">
                <i class='bx bxs-up-arrow-alt'></i>
            </a>
            <!--End Back To Top Button-->
            <footer class="page-footer">
                <p class="mb-0">Copyright © 2021. All right reserved.</p>
            </footer>
        </div>
        @include("layout.js")
    </body>
</html>