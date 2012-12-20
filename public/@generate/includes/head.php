<!-- HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- JQUERY -->
<script src="http://code.jquery.com/jquery-1.7.min.js"></script>

<!-- styles -->
<link href="bootstrap.css" rel="stylesheet"/>
<style type="text/css">
    /* Override some defaults */
    html, body {
        background-color: #eee;
    }
    body {
        padding-top: 40px; /* 40px to make the container go all the way to the bottom of the topbar */
    }
    .container > footer p {
        text-align: center; /* center align it with the container */
    }
    .container {
        width: 820px; /* downsize our container to make the content feel a bit tighter and more cohesive. NOTE: this removes two full columns from the grid, meaning you only go to 14 columns and not 16. */
    }

    /* The white background content wrapper */
    .container > .content {
        background-color: #fff;
        padding: 20px;
        margin: 0 -20px; /* negative indent the amount of the padding to maintain the grid system */
        -webkit-border-radius: 0 0 6px 6px;
        -moz-border-radius: 0 0 6px 6px;
        border-radius: 0 0 6px 6px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
        -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
        box-shadow: 0 1px 2px rgba(0,0,0,.15);
    }

    /* Page header tweaks */
    .page-header {
        background-color: #f5f5f5;
        padding: 20px 20px 10px;
        margin: -20px -20px 20px;
    }

    .topbar .btn {
        border: 0;
    }
</style>