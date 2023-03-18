<?php
include_once '/var/www/redOxford/config.php';
global $conn;

$alertsQuery = $conn->query("SELECT * FROM alerts WHERE user_id = $_SESSION[user_id]");
while ($alerts[] = $alertsQuery->fetch_assoc());
array_pop($alerts);

?>

<script>
var contacts = [];
function onClickEditSubmit(index, newCard = false) {
  //Reset modal content
  const alertData = new FormData();
  alertData.append('title', document.getElementById("title").value);
  alertData.append('url', document.getElementById("url").value);
  let selectedContacts = [];
  contacts.forEach((contact, i) => {
    if (document.getElementById(`${contact.id}-outlined`).checked) {
      selectedContacts.push(contact.id);
    }
  });
  alertData.append('contactIds', JSON.stringify(selectedContacts));
  axios.post('<?php echo baseUrl . '/src/server/add-new-alert.php'; ?>', alertData)
  .then((res) => {
    toggleEditModal();
    setTimeout(function () {

      location.reload();
    }, 100);
  })
  .catch((err) => {
    let { response } = err;
    alert(response ? `${response.data} - Code ${response.status}` : 'There was an issue, try again later.' );
  });
  return false;
}

function deleteAlert(alertId) {
  //Reset modal content
  console.log(alertId);
  const data = new FormData();
  data.append('alertId', alertId);

  axios.post('<?php echo baseUrl . '/src/server/delete-alert.php'; ?>', data)
  .then((res) => {
    location.reload();
  })
  .catch((err) => {
    let { response } = err;
    alert(response ? `${response.data} - Code ${response.status}` : 'There was an issue, try again later.' );
  });
  return false;
}

// Show Add Modal
function toggleEditModal() {
  EditModal.toggle();
}


// Pulls contact button modals and displays. Called at bottom of page as well as on add
function populateContactButtons() {
  axios.get('<?php echo baseUrl . '/src/server/get-user-contact-details.php'; ?>')
  .then((res) => {
    let contactContainer = document.getElementById('contact-buttons');
    contactContainer.innerHTML = '';
    contacts = res.data;
    res.data.forEach((contact, i) => {
      contactContainer.innerHTML += `
      <div class="col-12 col-md-6 p-1">
      <input type="checkbox" class="btn-check" name="options-outlined" id="${contact.id}-outlined" autocomplete="off">
      <label class="btn btn-outline-primary w-100 h-100" for="${contact.id}-outlined">${contact.contact_details}</label>
      </div>`;
    });
  }).catch((err) => {
    alert('There was an issue retreiving your contact details.', err);
  });
  return false;
}

function addContactDetails(type) {
  let input = document.getElementById(type === 'phone' ? 'phone-input' : 'email-input');
  let spinner = document.getElementById(type === 'phone' ? 'phone-spinner' : 'email-spinner');
  let buttonText = document.getElementById(type === 'phone' ? 'phone-button-text' : 'email-button-text');

  const newContactData = new FormData();
  newContactData.append('type', type);
  newContactData.append('value', input.value);

  spinner.classList.remove("visually-hidden");
  buttonText.classList.add("visually-hidden");

  axios.post('<?php echo baseUrl . '/src/server/add-contact-details.php'; ?>', newContactData)
  .then((res) => populateContactButtons())
  .catch((err) => alert('There was an issue adding your contact details.', err))
  .finally(() => {
    spinner.classList.add("visually-hidden");
    buttonText.classList.remove("visually-hidden");
  });
  return false; //Prevents page reload
}
</script>

<section class="section-container">
  <div class="row container-fluid content-row d-flex justify-content-around">

    <div class="col-6 p-3 d-flex justify-content-start align-items-center">
      <h1>Welcome <?php echo $_SESSION['username']; ?></h1>
    </div>

    <div class="col-6 p-3 d-flex justify-content-end align-items-center">
      <h2>Active Listings:&nbsp;<span class="fs-2"><?=count($alerts) ?>/5</span></h2>
    </div>

    <button class="col-10 col-md-6 col-lg-4 p-3 text-light rounded-pill bg-primary shadow d-flex flex-column align-items-center justify-content-center grow"  onclick="return toggleEditModal()">
      Add New Alert
    </button>

  </div>
</section>

<section class="section-container">

  <?php
  foreach($alerts as $index=>$alert): ?>
  <div class="row container-fluid content-row justify-content-center">

    <div class="col-12 col-lg-10 px-3 py-2">
      <div class="row rounded-pill bg-light shadow py-3 px-3 h-100 border">
        <div class="d-flex flex-column justify-content-center col-9">

          <h1 class="fs-3 text-dark"><?= $alert['title'] ?></h1>
          <span class="fs-6 text-dark text-nowrap text-muted text-truncate"><?= $alert['url'] ?></span>

        </div>
        <div class="col-3 d-flex flex-column align-items-start justify-content-center">
          <h1 class="fs-3 text-dark">Alert Checked:</h1>
          <span class="fs-6 text-dark text-nowrap text-muted text-truncate">100</span>
        </div>
      </div>
    </div>

    <div class="col-11 col-lg-1 px-2 py-">
      <button class="h-100 w-100 rounded-4 bg-danger shadow d-flex flex-column align-items-center justify-content-center grow" style="min-height: 40px;" onclick="return deleteAlert(<?=$alert['id']?>)">
        <h1 class="fs-5 text-light">Remove</h1>
      </button>
    </div>

    <div class="col-11 col-lg-1 px-2 py-1">
      <button class="h-100 w-100 rounded-4 bg-primary shadow d-flex align-items-center justify-content-center grow" style="min-height: 40px;">
        <h1 class="fs-5 text-light">Upgrade</h1>
      </button>
    </div>

  </div>
<?php endforeach; ?>

</section>

<!-- Modal -->
<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form
    class="modal-content bg-light rounded-4 border"
    id="add-form"
    name="add-form"
    data-name="add"
    onsubmit="return onClickEditSubmit()">
    <div class="modal-header border-0">
      <h5 class="modal-title text-dark" id="add-modal-label">Enter your new search information:</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
      <span class="text-dark mx-3">I want to scan this website:</span>

      <div class="form-floating my-3">
        <input type="title" class="form-control" id="title" placeholder="New Pokemon Cards in my area">
        <label for="title">Title</label>
      </div>

      <div class="form-floating my-3">
        <input type="url" class="form-control" id="url" placeholder="Kijiji.ca/...">
        <label for="url">URL</label>
      </div>

      <span class="text-dark m-3">When a new ad is up, contact me at:</span>
      <div id="contact-buttons" class="container-fluid d-flex justify-content-between flex-wrap">
      </div>

      <div class="form-floating my-3 d-flex">
        <input type="phone" class="form-control" id="phone-input" placeholder="519-000-1234">
        <label for="phone-input">New Phone Number</label>
        <button style="width: 50px; height: 50px;" class="btn btn-primary ms-3" onclick="return addContactDetails('phone')">
          <span id="phone-spinner" class="spinner-border spinner-border-sm text-light visually-hidden" role="status" aria-hidden="true"></span>
          <span id="phone-button-text" class="text-light">Add</span>
        </button>
      </div>

      <div class="form-floating my-3 d-flex">
        <input type="email" class="form-control" id="email-input" placeholder="myEmail@gmail.com">
        <label for="email-input">New Email</label>
        <button style="width: 50px; height: 50px;" class="btn btn-primary ms-3" onclick="return addContactDetails('email')">
          <span id="email-spinner" class="spinner-border spinner-border-sm text-light visually-hidden" role="status" aria-hidden="true"></span>
          <span id="email-button-text" class="text-light">Add</span>
        </button>
      </div>
    </div>

    <div class="modal-footer border-0">
      <button type="button" class="text-light btn btn-danger" data-bs-dismiss="modal">Cancel</button>
      <button class="text-light btn btn-success" type="submit">Submit</button>
    </div>
  </form>
</div>
</div>

<script>
populateContactButtons();
const EditModal = new bootstrap.Modal(document.getElementById("add-modal"));
</script>
