<?php
  include_once '../config.php';
  include rootPath . '/src/includes/check-auth-token.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <?php include_once rootPath.'/src/includes/site-head.php'; ?>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link href="/classifieds/classifieds.css" rel="stylesheet" type="text/css">
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>

<body class="bg-light">
  <script>

  function onCardClick(index, newCard = false) {
    //Reset modal content
    const editModal = new bootstrap.Modal(document.getElementById("edit-modal"));

    return editModal.show();
    newListingData.append('username', document.getElementById("username").value);
    newListingData.append('password', document.getElementById("password").value);
    newListingData.append('remember-me', document.getElementById("remember-me").checked);

    return false; //Prevents page reload
  }

  function onClickEditSubmit(index, newCard = false) {
    //Reset modal content
    console.log('FORM SUBMITED');
    return false;
    const editModal = new bootstrap.Modal(document.getElementById("edit-modal"));
    const newListingData = new FormData();

    return editModal.show();
    newListingData.append('username', document.getElementById("username").value);
    newListingData.append('password', document.getElementById("password").value);
    newListingData.append('remember-me', document.getElementById("remember-me").checked);

    axios.post('<?php echo baseUrl . '/src/server/'; ?>', newListingData)
    .then((res) => {
      console.log(res.data);

      location.reload();
      /*
      document.getElementById('edit-form').classList.remove("border-danger");
      document.getElementById('edit-form').classList.add("border-success");
      document.getElementById("edit-modal-label").innerText = 'Success!';
      document.getElementById("edit-modal-body").innerText = `Your user ID is ${res.data}`;
      editModal.show();*/
    }).catch((err) => {
      document.getElementById('edit-form').classList.remove("border-success");
      document.getElementById('edit-form').classList.add("border-danger");
      document.getElementById("edit-modal-label").innerText = 'Looks like there is a problem 🙅‍♂️';
      document.getElementById("edit-modal-body").innerText = err.response.data.replace('\r\n' ,"");
      editModal.show();
    });
    return false; //Prevents page reload
  }
</script>


<?php include rootPath . '/src/components/navigation-menu.php'; ?>

<section class="section-container">

  <div class="row container-fluid content-row d-flex justify-content-around">
    <div class="col-12 p-3 d-flex justify-content-center align-items-center">
      <h1>Welcome <?php echo $_SESSION['username']; ?></h1>
    </div>
  </div>

  <div class="row container-fluid content-row d-flex justify-content-around">

    <div class="col-6 p-3 d-flex justify-content-start align-items-center">
      <h2>Active Listings:</h2>
    </div>

    <div class="col-6 p-3 d-flex justify-content-end align-items-center">
      <span class="fs-2">0/5</span>
    </div>

    <div class="col-12 col-sm-5 rounded-3 bg-secondary border border-primary d-flex flex-column " style="min-height: 100px;">
      <h1 class="text-light">Title:</h1>
      <span class="text-light">url</span>
      <span class="text-light">Contact</span>
    </div>

    <button
    class="col-12 col-sm-5 rounded-3 bg-secondary border border-primary d-flex flex-column "
    style="min-height: 100px;"
    onclick="return onCardClick(-1, true)">
    <h1 class="text-light">add</h1>
  </button>

</div>
</section>

<!-- Modal -->
<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form
    class="modal-content bg-light rounded-4 border"
    id="edit-form"
    name="edit-form"
    data-name="edit"
    onsubmit="return onClickEditSubmit()">
    <div class="modal-header border-0">
      <h5 class="modal-title text-dark" id="edit-modal-label">Enter your new search information:</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
      <div class="form-floating">
        <input type="text" class="form-control" id="title" placeholder="New Houses">
        <label for="title">Alert Title</label>
      </div>
      <div class="form-floating">
        <input type="url" class="form-control" id="url" placeholder="Kijiji.ca/...">
        <label for="url">URL</label>
      </div>

      <span class="text-dark m-3">Contact me via:</span>
      <div class="container-fluid d-flex justify-content-around flex-wrap">
        <input type="radio" class="btn-check" name="options-outlined" id="phone-outlined" autocomplete="off" checked>
        <label class="btn btn-outline-primary col-12 col-sm-4" for="phone-outlined">Phone</label>

        <input type="radio" class="btn-check" name="options-outlined" id="email-outlined" autocomplete="off">
        <label class="btn btn-outline-primary col-12 col-sm-4" for="email-outlined">Email</label>

        <input type="radio" class="btn-check" name="options-outlined" id="both-outlined" autocomplete="off">
        <label class="btn btn-outline-primary col-12 col-sm-4" for="both-outlined">Both</label>
      </div>

      <?php //check  for contact info, if does not exist show option to add some ?>

    </div>



    <div class="modal-footer border-0">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      <button class="btn btn-success" type="submit">Submit</button>
    </div>
  </form>
</div>
</div>


</body>

<script>
// ToolTip Bootstrap function

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map((tooltipTriggerEl) => (new bootstrap.Tooltip(tooltipTriggerEl)));
</script>

</html>
