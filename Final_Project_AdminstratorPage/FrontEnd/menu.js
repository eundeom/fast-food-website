$(document).ready(function () {
    debugger;
    loadMenu();

    $('#addMenuBtn').on('click', function () {
        debugger;
        const prodName = $('#food').val();
        const quantity = $('#amount').val();
        const price = $('#price').val();
        const prodDescr = $('#description').val();
        const file = $('#file').val();
       

        $.ajax({
            url: 'http://localhost/php/Final_Project_AdminstratorPage/BackEnd/menu.php',
            method: 'POST',
            data: JSON.stringify({ prodName, quantity, price, prodDescr, file }),
            contentType: 'application/json',
            success: function (response) {
                console.log(response);
                debugger;
                loadMenu();
            },
            error: function (error) {
                console.log(error);
            },
        });
    });

});


const deleteHandler = (id) =>{
    debugger;
    $.ajax({
        url: 'http://localhost/php/Final_Project_AdminstratorPage/BackEnd/menu.php',
        method: 'DELETE',
        data: JSON.stringify({ id }),
        contentType: 'application/json',
        success: function (response) {
            console.log(response);
            debugger;
            loadMenu();
        },
        error: function (error) {
            console.log(error);
        },
    });
}

const EditHandler = (id, prodName, quantity, price, prodDescr) =>{
    debugger;
    $('#addMenuForm').on('click', function () {
        debugger;
        $('#food').val() = prodName;
        // const quantity = $('#amount').val();
        // const price = $('#price').val();
        // const prodDescr = $('#description').val();
    })
    
    debugger;
    $.ajax({
        url: 'http://localhost/php/Final_Project_AdminstratorPage/BackEnd/menu.php',
        method: 'PUT',
        data: JSON.stringify({ id, prodName, quantity, price, prodDescr }),
        contentType: 'application/json',
        success: function (response) {
            console.log(response);
            debugger;
            loadMenu();
        },
        error: function (error) {
            console.log(error);
        },
    });
}

const buttonPoper = (tr)=>{
    debugger;
    let td = document.createElement("td");
    let editBt = document.createElement("button");
    editBt.innerText = "Edit";
    editBt.className = "btn btn-primary"
    let delBt = document.createElement("button");
    delBt.innerText = "Delete";
    delBt.className = "btn btn-danger";
    let file = document.createElement("input");
    file.type="file";
    file.name="file";
    let submit = document.createElement("input");
    submit.type="submit";
    submit.name="submit";
    submit.addEventListener("click", ()=>{
        FileHandler();
    });
    td.append(editBt,delBt);
    tr.append(td);
    editBt.addEventListener("click", ()=>{
        EditHandler(tr.children[0].innerText, tr.children[1].innerText, tr.children[2].innerText, tr.children[3].innerText,tr.children[5].innerText);
    });
    delBt.addEventListener("click", ()=>{
        deleteHandler(tr.children[0].innerText);
    });
    return tr;

 }

 const FileHandler = (file, submit) =>{
    var formdata = new FormData();
    formdata.append("file", fileName);
    formdata.append("submit", btnName);

    var request = new XMLHttpRequest();
    request.open("POST", " http://localhost/php/Final_Project_AdminstratorPage/BackEnd/img_menu.php");
    request.send(formdata);
 }

const tablePoper = (data) => {
    debugger;
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
 }

function loadMenu() {
    debugger;
    $.ajax({
        method: 'GET',
        url: 'http://localhost/php/Final_Project_AdminstratorPage/BackEnd/menu.php',
        // url: '/BackEnd/administrator.php',
        success: function (response) {
            debugger;
            console.log(response);
            const menuList = $('#menuTable');
            menuList.empty();

            tablePoper(response);
        },
        error: function (error) {
            console.log(error);
        },
    });
}