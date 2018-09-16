const url = "http://127.0.0.1:8000/books/";
$( document ).ready(function() {
    getList();
    $("body").on("click", "a.main", function () {getList();});
    $("#list").on("click", "table a.view", function () {getView($(this).attr("id"));});
    $("#list").on("click", "table a.edit", function () {getEdit($(this).attr("id"));});
    $("#edit").on("click", "button.submit", function () {edit($(this).attr("id"));});
    $("#list").on("click", "a.create", function () {getCreate();});
    $("#create").on("click", "button.submit", function () {create();});
    $("#list").on("click", "table a.delete", function () {del($(this).attr("id"));});

    function getList() {
        $("#list").show();
        $(".hide").hide();
        $.getJSON(url, function(data){
            let table = "";
            $.each( data, function( index, rows ) {
                let id = 0;
                table += "<tr>";
                $.each( rows, function( key, value  ) {
                    if (key !== "id") {
                        table += "<td>" + value + "</td>";
                    }
                    else {
                        id = value;
                    }
                });
                table += "<td>" +
                    "<a class='view btn btn-info' id='"+ id +"' href='#'>View</a> " +
                    "<a class='edit btn btn-warning' id='"+ id +"' href='#'>Edit</a> " +
                    "<a class='delete btn btn-danger' id='"+ id +"' href='#'>Delete</a> " +
                    "</td>";
                table += "</tr>";
            });
            $("#list table tbody").html(table);
        });
    }

    function getEdit(id) {
        $.getJSON(url + id, function(data){
            let show = "<form>";
            $.each( data, function(key, value) {
                if (key !== "id") {
                    show += "<p class='form-group row'>" +
                        "<label  class='col-sm-1 col-form-label'>" + key.capitalize() + ": </label>" +
                        "<input  class='col-sm-3 form-control' name='" + key + "' value='" + value + "'>" +
                        "</p>";
                }
                else {
                    $("#edit button").attr("id", value);
                }
            });
            show += "</form>";
            $("#list").hide();
            $("#edit .content").html(show);
            $("#edit").show();
        });
    }

    function getView(id) {
        $.getJSON(url + id, function(data){
            let show = "";
            $.each( data, function(key, value) {
                if (key !== "id") {
                    show += "<p class='form-group row'>" +
                        "<label class='col-sm-1 col-form-label' >" + key.capitalize() + ": </label>" +
                        "<label class='col-sm-3 form-control-plaintext'>" + value + "</label></p>";
                }
            });
            $("#list").hide();
            $("#show .content").html(show);
            $("#show").show();
        });
    }

    function edit(id) {
        $("#edit .error").html('');
        let form = $('#edit form').serializeArray();
        let data = {};
        $.each(form, function (key, value) {
            data[value.name] = value.value;
        });

        $.ajax({
            url: url + id,
            method: "PUT",
            data: JSON.stringify(data),
            contentType: "application/json",
            dataType: "json",
        })
        .done(function() {
            getList();
        })
        .fail(function( jqXHR ) {
            let obj = JSON.parse(jqXHR.responseText);
            let errors = "";
            for (let field in obj) {
                errors += field.capitalize() + ": " + obj[field].message + "</br>";
            }
            $("#edit .error").html(errors)
        });
    }

    function getCreate() {

        $("#list").hide();
        $('#create form')[0].reset();
        $("#create").show();
    }

    function create() {
        $("#create .error").html('');
        let form = $('#create form').serializeArray();
        let data = {};
        $.each(form, function (key, value) {
            data[value.name] = value.value;
        });

        $.ajax({
            url: url,
            method: "POST",
            data: JSON.stringify(data),
            contentType: "application/json",
            dataType: "json",
        })
            .done(function() {
                getList();
            })
            .fail(function( jqXHR ) {
                let obj = JSON.parse(jqXHR.responseText);
                let errors = "";
                for (let field in obj) {
                    errors += "<p class='alert alert-danger'>" +
                        field.capitalize() + ": " + obj[field].message +
                        "</p>";
                }
                $("#create .error").html(errors)
            });
    }

    function del(id) {
        $.ajax({
            url: url + id,
            method: "DELETE",
        })
        .done(function() {
            getList();
        })
    }

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }
});
