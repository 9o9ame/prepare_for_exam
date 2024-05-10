<!doctype html>
<html lang="en">

<x-admin-header-css></x-admin-header-css>


<body>
    <!--wrapper-->
    <x-nav-bar></x-nav-bar>
    <div class="wrapper">
        @yield('content')
    </div>
    <!--end switcher-->
    <!-- Bootstrap JS -->
    <x-admin-footer-js></x-admin-footer-js>
</body>

</html>
