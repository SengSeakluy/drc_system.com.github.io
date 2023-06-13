const prevBtns = document.querySelectorAll(".btn-prev");
const progress = document.getElementById("progress");
const formSteps = document.querySelectorAll(".form-step");
const progressSteps = document.querySelectorAll(".progress-step");
let formStepsNum = 0;
let formOption1 = document.querySelector("#form-option-1");
let formOption2 = document.querySelector("#form-option-2");
let form1 = document.querySelector("#form-1");
let form2 = document.querySelector("#form-2");

prevBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    formStepsNum--;
    updateFormSteps();
    updateProgressbar();
  });
});

function updateFormSteps() {
  formSteps.forEach((formStep) => {
    formStep.classList.contains("form-step-active") &&
      formStep.classList.remove("form-step-active");
  });

  formSteps[formStepsNum].classList.add("form-step-active");
}

function updateProgressbar() {
  progressSteps.forEach((progressStep, idx) => {
    if (idx < formStepsNum + 1) {
      progressStep.classList.add("progress-step-active");
    } else {
      progressStep.classList.remove("progress-step-active");
    }
  });

  const progressActive = document.querySelectorAll(".progress-step-active");

  progress.style.width =
    ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
}

formOption1.addEventListener("change", () => {
  form1.style.display = "block";
  form2.style.display = "none";
});

formOption2.addEventListener("change", () => {
  form1.style.display = "none";
  form2.style.display = "block";
});

function firstNextButton() {
  var shipment = document.querySelector('input[name="shipment"]:checked').value;
  document.getElementById("shipments").innerHTML = shipment;
  var service = document.getElementById("service").value;
  document.getElementById("services").innerHTML = service;
  var serviceIn = document.getElementById("serviceIn").value;
  document.getElementById("servicesIn").innerHTML = serviceIn;
  formStepsNum++;
  updateFormSteps();
  updateProgressbar();
}

function firstSendAcrossNextButton() {
  var error = false;
  if ($("#serviceIn").val() == $("#serviceTo").val()) {
    $("#error-serviceIn").text("Please choose the right location.");
    $("#error-serviceTo").text("Please choose the right destination.");
    $("#serviceIn").addClass("box_error");
    $("#serviceTo").addClass("box_error");
    error = true;
  }
  var shipment = document.querySelector('input[name="shipment"]:checked').value;
  document.getElementById("shipments").innerHTML = shipment;
  var service = document.getElementById("service").value;
  document.getElementById("services").innerHTML = service;
  var serviceIn = document.getElementById("serviceIn").value;
  document.getElementById("servicesIn").innerHTML = serviceIn;
  var serviceTo = document.getElementById("serviceTo").value;
  document.getElementById("servicesTo").innerHTML = serviceTo;
  if (!error) {
    formStepsNum++;
    updateFormSteps();
    updateProgressbar();
  }
}

//username
$("#username").keyup(function () {
  var username = $("#username").val();
  if (username == "") {
    $("#error-username").text("Please enter your name.");
    $("#username").addClass("box_error");
    error = true;
  } else {
    $("#error-username").text("");
    error = false;
  }
  if (!isNaN(username)) {
    $("#error-username").text("Only Characters are allowed.");
    $("#username").addClass("box_error");
    error = true;
  } else {
    $("#username").removeClass("box_error");
  }
});
// phone
$("#phone").keyup(function () {
  var phone = $("#phone").val();
  if (phone == "") {
    $("#error-phone").text("please enter your phone number.");
    $("#phone").addClass("box_error");
    error = true;
  } else {
    $("#phone").removeClass("box_error");
    $("#error-phone").text("");
    if (!error) {
      errro = false;
    }
  }
  if (phone.length > 10) {
    $("#error-phone").text("Your phone number is over 10 Digits.");
    error = true;
  } else {
    $("#phone").removeClass("box_error");
  }
});
//email
$("#email").keyup(function () {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  if (!emailReg.test($("#email").val())) {
    $("#error-email").text("Please enter an Email address correctly.");
    $("#email").addClass("box_error");
    error = true;
  } else if ($("#email").val() == "") {
    $("#error-email").text("Please enter an Email address correctly.");
    $("#email").addClass("box_error");
    error = true;
  } else {
    $("#error-email").text("");
    if (!error) {
      errro = false;
    }
    $("#email").removeClass("box_error");
  }
});
// // house number
// $("#houseNumber").keyup(function () {
//   var houseNumber = $("#houseNumber").val();
//   if (houseNumber != houseNumber) {
//     $("#error-houseNumber").text("Please enter your house number.");
//     $("#houseNumber").addClass("box_error");
//     error = true;
//   } else if ($("#houseNumber").val() == "") {
//     $("#error-houseNumber").text("Please enter your house number.");
//     $("#houseNumber").addClass("box_error");
//     error = true;
//   } else {
//     $("#error-houseNumber").text("");
//     if (!error) {
//       errro = false;
//     }
//     $("#houseNumber").removeClass("box_error");
//   }
// });
// // street address
// $("#streetNumber").keyup(function () {
//   var streetNumber = $("#streetNumber").val();
//   if (streetNumber != streetNumber) {
//     $("#error-streetNumber").text("Please enter your street number.");
//     $("#streetNumber").addClass("box_error");
//     error = true;
//   } else if ($("#streetNumber").val() == "") {
//     $("#error-streetNumber").text("Please enter your street number.");
//     $("#streetNumber").addClass("box_error");
//     error = true;
//   } else {
//     $("#error-streetNumber").text("");
//     if (!error) {
//       errro = false;
//     }
//     $("#streetNumber").removeClass("box_error");
//   }
// });

function secondNextButton() {
  var error = false;
  // username
  if ($("#username").val() == "") {
    $("#error-username").text("Please enter your name.");
    $("#username").addClass("box_error");
    error = true;
  } else {
    var username = $("#username").val();
    if (username != username) {
      $("#error-username").text("Name is required.");
      error = true;
    } else {
      $("#error-username").text("");
      if (!error) {
        errro = false;
      }
      $("#username").removeClass("box_error");
    }
    if (!isNaN(username)) {
      $("#error-username").text("Only Characters are allowed.");
      error = true;
    } else {
      $("#username").removeClass("box_error");
    }
  }
  // phone
  if ($("#phone").val() == "") {
    $("#error-phone").text("Please enter your Phone number.");
    $("#phone").addClass("box_error");
    error = true;
  } else {
    var phone = $("#phone").val();
    if (phone != phone) {
      $("#error-phone").text("Phone number is required.");
      error = true;
    } else {
      $("#error-phone").text("");
      if (!error) {
        errro = false;
      }
    }
    if (phone.length > 10) {
      $("#error-phone").text("Your phone number is over 10 Digits.");
      error = true;
    } else {
      $("#phone").removeClass("box_error");
    }
  }
  // email
  if ($("#email").val() == "") {
    $("#error-email").text("Please enter an email address.");
    $("#email").addClass("box_error");
    error = true;
  } else {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if (!emailReg.test($("#email").val())) {
      $("#error-email").text("Please insert a valid email address.");
      error = true;
    } else {
      $("#error-email").text("");
      $("#email").removeClass("box_error");
    }
  }
  // // houseNumber
  // if ($("#houseNumber").val() == "") {
  //   $("#error-houseNumber").text("Please enter your unit address.");
  //   $("#houseNumber").addClass("box_error");
  //   error = true;
  // } else {
  //   var houseNumber = $("#houseNumber").val();
  //   if (houseNumber != houseNumber) {
  //     $("#error-houseNumber").text("unit address is required.");
  //     error = true;
  //   } else {
  //     $("#error-houseNumber").text("");
  //     $("#houseNumber").removeClass("box_error");
  //     if (!error) {
  //       errro = false;
  //     }
  //   }
  // }
  // // street Number
  // if ($("#streetNumber").val() == "") {
  //   $("#error-streetNumber").text("Please enter your street number.");
  //   $("#streetNumber").addClass("box_error");
  //   error = true;
  // } else {
  //   var streetNumber = $("#streetNumber").val();
  //   if (streetNumber != streetNumber) {
  //     $("#error-streetNumber").text("Street number is required.");
  //     error = true;
  //   } else {
  //     $("#error-streetNumber").text("");
  //     if (!error) {
  //       errro = false;
  //     }
  //   }
  //   if (streetNumber.length >= 5) {
  //     $("#error-streetNumber").text("Mobile number must smaller than 6 digit.");
  //     error = true;
  //   } else {
  //     $("#streetNumber").removeClass("box_error");
  //   }
  // }
  if (!error) {
    var username = document.getElementById("username").value;
    var phone = document.getElementById("phone").value;
    var email = document.getElementById("email").value;
    var address = document.getElementById("address").value;
    // var houseNumber = document.getElementById("houseNumber").value;
    // var streetNumber = document.getElementById("streetNumber").value;
    // var distract = document.getElementById("distract").value;
    // var commune = document.getElementById("commune").value;
    var Rec_Name = document.getElementById("Rec_Name").value;
    var Rec_Phone = document.getElementById("Rec_Phone").value;
    var Rec_Email = document.getElementById("Rec_Email").value;
    var Rec_house_number = document.getElementById("Rec_house_number").value;
    var Rec_street_address = document.getElementById("Rec_street_address").value;
    var Rec_District = document.getElementById("Rec_District").value;
    var Rec_Commune = document.getElementById("Rec_Commune").value;
    document.getElementById("ans_username").innerHTML = username;
    document.getElementById("ans_phone").innerHTML = phone;
    document.getElementById("ans_email").innerHTML = email;
    document.getElementById("ans_address").innerHTML = address;
    // document.getElementById("ans_houseNumber").innerHTML = houseNumber;
    // document.getElementById("ans_streetNumber").innerHTML = streetNumber;
    // document.getElementById("ans_distract").innerHTML = distract;
    // document.getElementById("ans_commune").innerHTML = commune;
    document.getElementById("ans_Reciever").innerHTML = Rec_Name;
    document.getElementById("ans_Rec_Phone").innerHTML = Rec_Phone;
    document.getElementById("ans_Rec_Email").innerHTML = Rec_Email;
    document.getElementById("ans_Rec_house_number").innerHTML = Rec_house_number;
    document.getElementById("ans_Rec_street_address").innerHTML = Rec_street_address;
    document.getElementById("ans_Rec_District").innerHTML = Rec_District;
    document.getElementById("ans_Rec_Commune").innerHTML = Rec_Commune;
    formStepsNum++;
    updateFormSteps();
    updateProgressbar();
  }
}

function thirdNextButton() {
  formStepsNum++;
  updateFormSteps();
  updateProgressbar();
}
const formStep = document.getElementById("formStep");
const addItemButton = document.getElementById("addItemButton");
const removeButtons = document.querySelectorAll(".removeButton");
const nextButton = document.getElementById("nextButton");

// Add event listeners
addItemButton.addEventListener("click", addItem);
removeButtons.forEach((button) => button.addEventListener("click", removeItem));
document
  .querySelectorAll("[required]")
  .forEach((field) => field.addEventListener("change", checkFormCompletion));

// Function for adding a new item
function addItem() {
  const item = document.querySelector(".item");
  const newItem = item.cloneNode(true);
  const removeButton = newItem.querySelector(".removeButton");
  const itemIndex = document.querySelectorAll(".item").length;

  newItem.querySelectorAll("[name]").forEach((field) => {
    field.name = `${field.name.substring(
      0,
      field.name.length - 2
    )}[${itemIndex}]`;
    field.value = "";
    field.addEventListener("change", checkFormCompletion);
  });

  if (itemIndex > 0) {
    addItemButton.classList.add("disabled");
    removeButton.classList.remove("disabled");
  }

  removeButton.addEventListener("click", removeItem);

  itemsContainer.appendChild(newItem);
  checkFormCompletion();
}

// Function for removing an item
function removeItem(event) {
  event.preventDefault();
  const item = event.target.closest(".item");
  const removeButton = item.querySelector(".removeButton");

  if (document.querySelectorAll(".item").length > 1) {
    addItemButton.classList.remove("disabled");
    removeButton.classList.add("disabled");
    item.remove();
    checkFormCompletion();
  }
}

// Function for checking form completion and enabling/disabling the "Next" button
function checkFormCompletion() {
  const requiredFields = formStep.querySelectorAll("[required]");
  const filledOutFields = Array.from(requiredFields).filter(
    (field) => field.value !== ""
  );

  if (requiredFields.length === filledOutFields.length) {
    nextButton.classList.remove("disabled");
  } else {
    nextButton.classList.add("disabled");
  }
}

function submitForm() {
  if (validateForm() == false) {
    document.getElementById("my-form").submit();
  } else {
    console.log("error");
  }
}

function validateForm() {
  var error = false;
  if (formOption1.checked) {
    // Check if the datepicker and time fields are filled in
    const datepickerValue = document.querySelector("#datepicker").value;
    if (datepickerValue == "") {
      $("#error-datepicker").text("Please choose your pickup date");
      $("#datepicker").addClass("box_error");
      return (error = true);
    } else {
      $("#error-datepicker").text("");
      if (!error) {
        return (errro = false);
      }
      $("#datepicker").removeClass("box_error");
    }
  } else if (formOption2.checked) {
    const postofficeValue = document.querySelector("#postoffice").value;
    if (postofficeValue == "") {
      $("#error-postoffice").text("please pick the post office");
      $("#postoffice").addClass("box_error");
      return (error = true);
    } else {
      return (error = false);
    }
  }
}

$(function () {
  $("#datepicker").datepicker({
    minDate: 0,
    dateFormat: "dd/mm/yy",
    onSelect: function () {
      var datepicker = $("#datepicker").val();
      if (datepicker == "") {
        $("#error-datepicker").text("please pick your sending date.");
        $("#datepicker").addClass("box_error");
        error = true;
      } else {
        $("#datepicker").removeClass("box_error");
        $("#error-datepicker").text("");
        errro = false;
      }
    },
  });
});

var btn = $('.toTheTop');

btn.on('click', function(e) {
  e.preventDefault();
  $('html, body').animate({scrollTop:0}, '300');
});
