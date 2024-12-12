<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="admin_assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="admin_assets/img/favicon.png">
  <title>
    Admin
  </title>
  <base href="{{asset('')}}">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="admin_assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="admin_assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="admin_assets/css/nucleo-svg.css" rel="stylesheet" />
  <link id="pagestyle" href="admin_assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="">
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
              <div class="card card-plain">
                <div class="card-header pb-0 text-start">
                  @if(count($errors) > 0)
                  <div class="alert alert-danger">
                    @foreach($errors->all() as $arr)
                    {{ $arr }}<br>
                    @endforeach
                  </div>
                  @endif
                  @if (session('warning'))
                  <div class="alert alert-warning">
                    {{ session('warning') }}
                  </div>
                  @endif
                  <h4 class="font-weight-bolder">Sign In</h4>
                  <p class="mb-0">Enter your email and password to sign in</p>
                </div>
                <div class=" card-body">
                  <form action="{{ route('login') }}" method="POST">
                    @csrf

                
                    <div class="mb-3">
                 
                        <input type="tel" id="phone" name="Phone" class="form-control form-control-lg" placeholder="Số điện thoại" aria-label="Số điện thoại" pattern="\d{10}" title="Vui lòng nhập số điện thoại gồm 10 chữ số" required autocomplete="off">
                        <small id="phoneError" class="form-text text-danger" style="display:none;">Vui lòng nhập số điện thoại hợp lệ (10 chữ số).</small>
                      </div>
                      <div class="mb-3">
                        <input type="password" name="Password" class="form-control form-control-lg" placeholder="Mật khẩu" aria-label="Mật khẩu" required>
                      </div>
                      
                      {{-- <script>
                        const phoneInput = document.getElementById('phone');
                        const phoneError = document.getElementById('phoneError');
                        
                        phoneInput.addEventListener('input', function () {
                          const phoneValue = phoneInput.value;
                          
                        
                          const isValidPhone = /^\d{10}$/.test(phoneValue);
                          
                          if (!isValidPhone) {
                            phoneError.style.display = 'block';
                          } else {
                            phoneError.style.display = 'none';
                          }
                        });
                      </script> --}}
                      {{-- <script>
                        document.querySelector('form').addEventListener('submit', function (event) {
                          if (document.getElementById('rememberMe').checked) {
                           
                            document.cookie = "rememberMe=true; path=/; max-age=" + (60 * 60 * 24 * 30); // Cookie sẽ hết hạn sau 30 ngày
                          } else {
                            document.cookie = "rememberMe=false; path=/; max-age=0"; // Xóa cookie nếu không chọn "Remember me"
                          }
                        });
                      </script> --}}
                      
                      
                      
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg');
               background-size: cover;">
                <span class="mask bg-gradient-primary opacity-6"></span>
                <h4 class="mt-5 text-white font-weight-bolder position-relative">"Attention is the new currency"</h4>
                <p class="text-white position-relative">The more effortless the writing looks, the more effort the writer actually put into the process.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  @yield('scripts')
</body>

</html>
