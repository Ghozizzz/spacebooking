<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>UPH Class Management Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.css" rel="stylesheet">
  @yield('script')
</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-4 p-5 text-center">
                <img class="img-fluid my-2" src="https://learn.uph.edu/pluginfile.php/1/theme_maker/logo/1579580801/UPH_Logo_Pantone_resize%20%282%29.png">
              </div>
              <div class="col-lg-8">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                  </div>
                  <form class="user">
                    <a href="{{route('login.signin')}}" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-microsoft fa-fw"></i> Login with Office365
                    </a>
                  </form>

                    <a href="{{route('login.signin_test')}}" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-microsoft fa-fw"></i> Login with Office365 Test
                    </a>
                  @if($errors->any())
                  {!! implode('', $errors->all('
                    <div class="alert alert-danger my-2 text-center" role="alert">
                      :message
                    </div>
                  ')) !!}
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
