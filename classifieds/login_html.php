
<script>

function login() {
  //Reset modal content
  document.getElementById("login-modal-label").innerText = 'Message Loading...';
  document.getElementById("login-modal-body").innerText = '';

  const loginModal = new bootstrap.Modal(document.getElementById("login-modal"));
  const loginData = new FormData();

  loginData.append('username', document.getElementById("username").value);
  loginData.append('password', document.getElementById("password").value);
  loginData.append('remember-me', document.getElementById("remember-me").checked);

  axios.post('<?php echo baseUrl . '/src/server/login.php'; ?>', loginData)
  .then((res) => {
    console.log(res.data);

    location.reload();
    /*
    document.getElementById('modal-box').classList.remove("border-danger");
    document.getElementById('modal-box').classList.add("border-success");
    document.getElementById("login-modal-label").innerText = 'Success!';
    document.getElementById("login-modal-body").innerText = `Your user ID is ${res.data}`;
    loginModal.show();*/
  }).catch((err) => {
    document.getElementById('modal-box').classList.remove("border-success");
    document.getElementById('modal-box').classList.add("border-danger");
    document.getElementById("login-modal-label").innerText = 'Looks like there is a problem 🙅‍♂️';
    document.getElementById("login-modal-body").innerText = err.response.data.replace('\r\n' ,"");
    loginModal.show();
  });
  return false; //Prevents page reload
}
</script>

<section class="section-container" style="min-height: 90vh;">
  <div class="row container-fluid content-row d-flex justify-content-around ">

    <div class="border-bottom border-primary col-12 p-3 d-flex justify-content-center align-items-center">
      <img src="/src/images/kijiji.svg" class="img-fluid me-3" style="max-height:70px;" /><h2>Speed Tracker</h2>
    </div>
    <div class="col-12 p-3">
      <p class="fs-5">
        Need to be the first person to respond to a Kijiji ad?
      </p>
      <p class="fs-4">
        Get a notification within <strong>2 minutes</strong> of an ad you are interested in going up.
      </p>
    </div>

    <form
    class="col-8 col-md-4"
    id="login-form"
    name="login-form"
    data-name="login"
    onsubmit="return login()">
    <h1 class="fs-3 m-3">Please sign in to start</h1>

    <div class="form-floating">
      <input type="username" class="form-control" id="username" placeholder="myUsername">
      <label for="username">username</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="password" placeholder="Password">
      <label for="password">Password</label>
    </div>

    <div class="checkbox m-3">
      <label>
        <input type="checkbox" id="remember-me" name="rm"> Use Cookies to remember me
      </label>
    </div>

    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>

  </form>

  <!-- Modal -->
  <div class="modal fade" id="login-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark rounded-4 border" id="modal-box">
        <div class="modal-header">
          <h5 class="modal-title text-light" id="login-modal-label"></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <span class="text-light" id="login-modal-body"></span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Okay</button>
        </div>
      </div>
    </div>
  </div>

</div>
</section>