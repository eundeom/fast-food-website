///////// FOR CUSTOMER ///////////

class Item {
  constructor(id, product, selctAmount, cost) {
    this.id = id;
    this.product = product;
    this.selctAmount = selctAmount;
    this.cost = cost;
  }
}

let userInfo = JSON.parse(sessionStorage.getItem("user"));

let selectedItems = [];

$(document).ready(function () {
  loadMenuForC();

  $("#saveMenuButton").on("click", function () {
    const userDataForC = {
      user: JSON.parse(sessionStorage.getItem("user")).id,
      prod: JSON.stringify(selectedItems),
    };

    $.ajax({
      url: "http://localhost/php/fast-food-website/BackEnd/menu.php",
      method: "POST",
      data: JSON.stringify(userDataForC),
      contentType: "application/json",
      success: function (response) {
        console.log(response);
        location.replace("./orderhistory.html");
        // loadMenuForC();
      },
      error: function (error) {
        location.replace("./orderhistory.html");
        console.log(error);
      },
      dataType: "json",
    });
  });
});

const FileHandlerForC = (clickedBtn, data) => {
  // Get the text and price of the clicked item
  var selectedItem = $(clickedBtn).parent().parent().children(); //slice the string without "add"
  var itemPrice = parseFloat($(clickedBtn).parent().parent().children()[3]);
  var productName = $(clickedBtn).parent().parent().children().eq(1).text();
  let editItem = null;

  // append at selectedItems
  // if it already exit in the selectedItem array -> change only amount
  selectedItems.forEach(function (val, idx) {
    if (val.product == productName) {
      editItem = val;
    }
  });

  if (editItem) {
    // add one more amount
    editItem.selctAmount++;
  } else {
    $.each(data, function (idx, value) {
      // console.log(value);
      // if the value.product and productName is same,
      if (value.prodName == productName) {
        let slcObj = new Item(value.id, value.prodName, 1, value.price);
        selectedItems.push(slcObj);
      }
    });
  }
  console.log(selectedItems);
};

const buttonPoperForC = (tr, data) => {
  let td = document.createElement("td");
  let addBt = document.createElement("button");
  addBt.innerText = "Add";
  addBt.className = "btn btn-info";

  addBt.addEventListener("click", () => {
    FileHandlerForC(addBt, data);
  });
  td.append(addBt);
  tr.append(td);
  return tr;
};

const tablePoperForC = (data) => {
  document.querySelector("table").removeAttribute("style");
  for (let m of data) {
    let tr = document.createElement("tr");
    for (let p in m) {
      let td = document.createElement("td");
      td.innerText = m[p];
      tr.append(td);
    }
    document.querySelector("tbody").append(buttonPoperForC(tr, data));
  }
};

function loadMenuForC() {
  $.ajax({
    method: "GET",
    url: "http://localhost/php/fast-food-website/BackEnd/menu.php",
    // data:{ "req":""},
    success: function (response) {
      // console.log(response);
      const menuList = $("#menuTableForC");
      menuList.empty();

      tablePoperForC(response);
    },
    error: function (error) {
      console.log(error);
    },
  });
}
