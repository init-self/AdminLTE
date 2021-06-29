var file_list = [];



$(function()
{
    
    /** -----------------------------------------SHOW RECORDS--------------------------------------------------
     * 
     * 
     * To SHOW the data from the database when page loads
     * 
     * Aim:
     * extract id from the URL
     * post id with flag to php file
     * show records in table format
     */

    // extract the id from the URL
    let id = window.location.href;
    id = id.slice(id.lastIndexOf('id')+3)

    // post the id with required flag
    $.post("../database/project-edit.php", {id: id, flag: "show"}, (data, status) =>
    {
        var data = JSON.parse(data);
        // console.log(data); // debugging purposes

        // if records are found
        if(data.records)
        {
            // set all the fields with their respective data
            $('#inputName').attr('value', data.projects.Name)
            $('#inputDescription').text(data.projects.Description)
            $('#inputStatus option[value=\"' + data.projects.Status + '\"').attr('selected', 'selected')
            $('#inputClientCompany').attr('value', data.projects.ClientCompany);
            $('#inputProjectLeader').attr('value', data.projects.ProjectLeader);
            $('#inputEstimatedBudget').attr('value', data.budgets.Budget);
            $('#inputSpentBudget').attr('value', data.budgets.Spent);
            $('#inputEstimatedDuration').attr('value', data.budgets.Duration);

            // set anchor add file tag attribute to file_upload page
            $('a[id="anchorAddFile"]').attr('href', `../database/file_upload.php?id=${id}`)

            // extract file names and sizes from the data object - to set file's data in table format
            let file_names = data.files; // array of file names
            let file_sizes = data.size; // array of file sizes

            // set files with their data
            for(let i = 0; i < file_names.length; i++)
            {
                let txt1 = `<tr id="${id}">
                <td>${file_names[i]}</td>
                <td>${file_sizes[i]}</td>
                <td class="text-right py-0 align-middle">
                    <div class="btn-group btn-group-sm">
                        <a href="../uploads/${file_names[i]}" target="_blank" class="btn btn-info"><i class="fas fa-eye"></i></a>
                        <a href="" class="btn btn-danger" onclick="file_delete(${id}, ${file_names[i]}, ${file_sizes['i']})" id="file_delete"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
                </tr>`
                $('#row_list').append(txt1);
            }
        }
    });
    // end of show data


    
    /** -----------------------------------UPDATE RECORDS--------------------------------------------------------
     * 
     * 
     * To save changes in database when user clicks SAVE CHANGES button
     * 
     * collect all the data
     * post the data to the php file
     * show error messages if any, else show success message and redirect to projects page
     */

    // on save changes button click post updated data to php file
    $('input[value="Save Changes"]').on('click', function()
    {
        // collect data
        var formData = {
            flag: "update",
            name: $('#inputName').val(),
            desc: $('#inputDescription').val(),
            status: $('#inputStatus').val(),
            client: $('#inputClientCompany').val(),
            leader: $('#inputProjectLeader').val(),
            budget: parseInt($('#inputEstimatedBudget').val()),
            spent: parseInt($('#inputSpentBudget').val()),
            duration: parseInt($('#inputEstimatedDuration').val()),
            id: id
        }
        // post data with flag to php file
        $.post("../database/project-edit.php", formData, (data, status) =>
        {
            var data = JSON.parse(data);
            // console.log(data); // debugging purpose

            // on successful updation
            if(data.success)
            {
                // show success modal
                $('#Modal').modal("show");
                $('#Modal #header').html("Success <i class='far fa-check-circle'></i>");
                $('#Modal #body').text("Records Saved Successfully!");
                setTimeout(function()
                {
                    window.location = "./projects.php"; // redirect to projects page after 1 second
                }, 1000)
            }else
            {
                // show error modal and hide the modal
                $('#Modal').modal("show");
                $('#Modal #header').attr('class', 'text-danger');
                $('#Modal #body').attr('class', 'text-danger');
                $('#Modal #header').html("<i class='fas fa-exclamation mx-4'></i>Error");
                $('#Modal #body').text(data.errors);
                setTimeout(function()
                {
                    $('#Modal').modal('hide'); // hide error modal after 1.5 seconds
                }, 1500)
            }
        });
    });
    
});

