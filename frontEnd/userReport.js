$(document).ready(function () {
  loadUsers();
});

const buttonPoper = (tr) => {
  let td = document.createElement("td");
  tr.append(td);
  return tr;
};

const tablePoper = (data) => {
  document.querySelector("table").removeAttribute("style");
  for (let m of data) {
    let tr = document.createElement("tr");
    for (let p in m) {
      let td = document.createElement("td");
      td.innerText = m[p];
      tr.append(td);
    }
    document.querySelector("tbody").append(buttonPoper(tr));
  }
};

function loadUsers() {
  $.ajax({
    method: "GET",
    url: "http://localhost/php/fast-food-website/BackEnd/userReport.php",
    success: function (response) {
      console.log(response);
      const userList = $("#userTable");
      userList.empty();

      tablePoper(response);
    },
    error: function (error) {
      console.log(error);
    },
  });
}
