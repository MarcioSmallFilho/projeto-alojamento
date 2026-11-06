<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="../elFinder/css/elfinder.min.css">
  <link rel="stylesheet" href="../elFinder/css/theme.css">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <script src="../elFinder/js/elfinder.min.js"></script>
</head>
<body>
  <div id="elfinder"></div>

  <script>
    $(function () {
      $('#elfinder').elfinder({
        url: '../PHP/conector.php',
        lang: 'pt_BR'
      });
    });
  </script>
</body>
</html>