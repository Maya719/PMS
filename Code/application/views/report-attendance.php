<?php $this->load->view('includes/head'); ?>
<style>
  .hidden{
    display: none;
  }
  /* Target all cells */
.table th {
  font-weight: bold;
  font-size: 12px;
  max-height: 20px;
  overflow: hidden;
}

.table td {
  /* Your styling here */
  font-weight: bold;
  font-size: 9px;
}


.green-background{
  background-color: #efffc7 !important; /* Remove background color */
}

.left-pad{
  padding-left:0.5em !important;
  padding-right:0.5em !important;
  padding-bottom:0.5em !important;
  padding-top:0.5em !important;
} 

#attendance_list th{
  height: 30px;
  background-color: #f1f1f1; /* Replace with your desired background color */
}


.loader-container {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.2); /* Light gray color with opacity */
    z-index: 9999; /* Set a high z-index to ensure it's above other content */
}

.loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <?php $this->load->view('includes/navbar'); ?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <div class="section-header-back">
              <a href="javascript:history.go(-1)" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>
            <?=$this->lang->line('attendance')?htmlspecialchars($this->lang->line('attendance')):'Attendance Report'?> 
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('attendance')?htmlspecialchars($this->lang->line('attendance')):'Attendance Report'?></div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
            <div class="form-group col-md-6">
                  <select onchange="filter()" class="form-control select2" id="active_users">
                    <option value="3"><?=$this->lang->line('select_department')?$this->lang->line('select_department'):'All'?></option>
                    <option value="1"><?=$this->lang->line('select_department')?$this->lang->line('select_department'):'Active'?></option>
                    <option value="2"><?=$this->lang->line('select_department')?$this->lang->line('select_department'):'Inactive'?></option>
                  </select>
              </div>
              <div class="form-group col-md-6">
                <select onchange="filter()" class="form-control select2" id="attendance_filter_user">
                  <option value=""><?=$this->lang->line('select_users')?$this->lang->line('select_users'):'Select Users'?></option>
                  <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
                  <option value="<?=$system_user->employee_id?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                  <?php } } ?>
                </select>
            </div>
              <div class="form-group col-md-6">
                <select onchange="filter()" class="form-control select2" id="department">
                  <option value=""><?=$this->lang->line('select_department')?$this->lang->line('select_department'):'Select Department'?></option>
                  <?php foreach($departments as $department){ ?>
                    <option value="<?= $department['id'] ?>"><?= $department['department_name'] ?></option>
                  <?php 
                }?>
                </select>
              </div>
                <div class="form-group col-md-6">
                  <select class="form-control select2" id="attendance_filter" onchange="filter()">
                    <option value="today"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Today'?></option>
                    <option value="ystdy"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Yesterday'?></option>
                    <option value="tweek"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Week'?></option>
                    <option value="tmonth" selected><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Month'?></option>
                    <option value="lmonth"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Last Month'?></option>
                    <option value="tyear"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Year'?></option>
                    <option value="lyear"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Last Year'?></option>
                    <option value="custom"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Custom'?></option>
                  </select>
                </div>
                <div id="myDiv" class="form-group col-md-6 hidden">
                  <input onchange="filter()" type="text" name="from" id="from" class="form-control">
                </div>
                <div id="myDiv2" class="form-group col-md-6 hidden">
                  <input onchange="filter()" type="text" name="too" id="too" class="form-control">
                </div>
                <!-- <div class="form-group col-md-2">
                  <button type="button" class="btn btn-primary btn-lg btn-block" id="filter" onclick="filter()">
                    <?=$this->lang->line('filter')?$this->lang->line('filter'):'Filter'?>
                  </button>
                </div> -->
            </div>
             <!-- Toolbar buttons -->
             <div class="toolbar" id="toolbar">
              <button class="btn btn-secondary" onclick="printAttendanceData()">Download Pdf</button>
            </div>
            <div class="row">
                <div class="col-md-12">
                  <div class="card card-primary">
                    <div class="card-body"> 
                    <table
                      id="attendance_list"
                      data-toggle="table"
                      data-pagination="true"
                      data-height="800"
                      data-pagination-h-align="left"
                      data-pagination-detail-h-align="right"
                      data-sticky-header=true
                      data-click-to-select="true"
                      data-page-list="[5, 10, 20, 50, 100, 200]"
                      data-show-refresh="false" 
                      data-trim-on-search="false"
                      data-sort-name="id" 
                      data-sort-order="desc"
                      data-mobile-responsive="true"
                      data-toolbar="#toolbar"
                      data-show-export="true" 
                      data-export-types="['json', 'csv', 'txt', 'sql', 'doc', 'excel', 'pdf']"
                      data-maintain-selected="true"
                      data-url="attendance/get_attendance_report">
                        <!-- start preloader -->
                        <div class="preloader">
                            <div class="sk-spinner sk-spinner-rotating-plane"></div>
                        </div>
                        <div class="loader-container">
                            <div class="loader"></div>
                        </div>
                        <!-- end preloader -->
                    </table>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </section>
      </div>
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>


<?php $this->load->view('includes/js'); ?>

<script>
    function showLoader() {
    $('.loader-container').css('display', 'block');
}

function hideLoader() {
    $('.loader-container').css('display', 'none');
}
</script>
<script>

  function filter(){
  var select = document.getElementById("attendance_filter");
  var div = document.getElementById("myDiv");
  var div2 = document.getElementById("myDiv2");
  if (select.value === "custom") {
    div.classList.remove("hidden");
    div2.classList.remove("hidden");
  } else {
    div.classList.add("hidden");
    div2.classList.add("hidden");
  }
    const table = $('#attendance_list');
    // Get the current limit (number of rows per page)
    const limit = table.bootstrapTable('getOptions').pageSize;
    // Get the current offset (page number)
    const offset = table.bootstrapTable('getOptions').pageNumber;
    console.log($('#department').val());
    var data = {
  "active_users": $('#active_users').val(),
  "user_id": $('#attendance_filter_user').val(),
  "filter": $('#attendance_filter').val(),
  "departments": $('#department').val(),
  "from": $('#from').val(),
  "limit": offset,
  "offset": offset,
  "too": $('#too').val()
};
showLoader();
$.ajax({
  url: '<?= base_url('attendance/get_attendance_report') ?>', // Replace with your actual API URL
  type: 'POST', // Adjust the request method if needed
  data: data,
  success: function(response) {
    hideLoader();
    var tableData = JSON.parse(response);
    console.log(tableData);


    // Replace the table header with the new headings
    var tableHead = $('#attendance_list thead');
    tableHead.empty(); // Clear existing table header
    
    if (!tableData.rows || tableData.rows.length === 0) {
      // If no headings, display a single cell with "No Matching Records Found"
      var noRecordsRow = '<tr><td class="bold" style="padding-left:200px !important; padding-right:200px !important;" colspan="2"><center>No Matching Records Found</center></td></tr>';
      tableHead.append(noRecordsRow);
      
      $('#attendance_list').css('overflow-y', 'hidden'); // Ensure overflow is set to 'auto' or 'scroll'

      // Clear the table body as well
      var tableBody = $('#attendance_list tbody');
      tableBody.empty();

      return;
    }

    var headerRow = '<tr>' +
    '<th class="nopad transparent-cell" colspan="3">&nbsp;</th>';
      
    // Add the headings for each date
    $.each(tableData.month, function (index, month) {
      var monthKey = new Date(month).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
      daysCount = 0;
      $.each(tableData.headings, function (index, date) {
        if (new Date(date).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }) === monthKey) {
          daysCount++;
        }
      });
      headerRow += '<th class="left-pad transparent-cell" colspan="' + daysCount + '"><center>' + month + '</center></th>';
    });

    headerRow += '</tr>'; // Close the first header row

    // Add the headings for each date
    headerRow += '<tr>' +
      '<th data-fixed-col data-field="sno" data-sortable="false" class="left-pad"></th>' + // S.no column header
      '<th data-fixed-col data-field="user" data-sortable="false" class="left-pad">' +
      '<?= $this->lang->line('team_members') ? $this->lang->line('team_members') : '' ?>' +
      '</th>' +
      '<th data-fixed-col data-field="additional_column" data-sortable="false" class="left-pad"></th>'; // Additional column header

      var customFilter = $('#attendance_filter').val();
      function getDayFromDate(date) {
      var parsedDate = new Date(date);
      var options = { weekday: 'short' };
      var formattedDay = parsedDate.toLocaleString('en-US', options);
      return formattedDay;
    }
    function formatDate(date) {
      var parsedDate = new Date(date);
      var formattedDate = parsedDate.toLocaleDateString('en-US', {
        day: 'numeric'
      });
      return formattedDate;
    }

      function formatDate2(date) {
      var parsedDate = new Date(date);
      var month = parsedDate.toLocaleDateString('en-US', { month: 'short' });
      var day = parsedDate.getDate();
      return day + ' ' + month;
    }
      // Determine the format function based on customFilter value
      var formatDateFunction = (customFilter === 'custom') ? formatDate2 : formatDate;

      // Add the headings for each date
      $.each(tableData.headings, function(index, date) {
          headerRow += '<th data-field="' + date + '" data-sortable="false" class="left-pad">' + formatDateFunction(date) + '</th>';
      });

    headerRow += '</tr>'; // Close the table header row
        // Add the headings for each date
        headerRow += '<tr>' +
      '<th style="width: 20px" data-field="sno" data-sortable="false" class="left-pad">S.no</th>' + // S.no column header
      '<th style="width: 70px" data-field="user" data-sortable="false" class="left-pad">' +
      '<?= $this->lang->line('team_members') ? $this->lang->line('team_members') : 'Team Members' ?>' +
      '</th>' +
      '<th style="width: 70px" data-field="additional_column" data-sortable="false" class="left-pad">Absent/ HD/Late</th>'; // Additional column header

    // Add the headings for each date
    $.each(tableData.headings, function(index, date) {
      headerRow += '<th data-field="' + date + '" data-sortable="false" class="left-pad">' + getDayFromDate(date) + '</th>';
    });

    function formatDate(date) {
      var parsedDate = new Date(date);
      var formattedDate = parsedDate.toLocaleDateString('en-US', {
        day: 'numeric'
      });
      return formattedDate;
    }

    headerRow += '</tr>'; // Close the table header row
    tableHead.append(headerRow); // Append the header rows to the table header

    // Replace the table body with the new data
    var tableBody = $('#attendance_list tbody');
    tableBody.empty(); // Clear existing table rows

    $.each(tableData.rows, function(index, row) {
      var rowData = '<tr>' +
        '<td class="left-pad" data-fixed-col>' + (index + 1) + '</td>' + // S.no column value
        '<td class="left-pad" data-fixed-col>' + row.user + '</td>' + // Add the user data
        '<td class="left-pad" data-width="10%">' + row.sq + '</td>'; // Additional column value

      // Add the attendance data for each date
      $.each(tableData.headings, function(index, date) {
        rowData += '<td class="left-pad">' + (row[date] ? row[date] : '') + '</td>';
      });

      rowData += '</tr>'; // Close the table row

      tableBody.append(rowData); // Append the row to the table body
    });
  },
  error: function(xhr, status, error) {
    // Handle any errors that occur during the AJAX request
    console.log('Error:', error);
    
    // If you want to see the full XMLHttpRequest object and its properties
    console.log("Response:", xhr.responseText); // This will contain the detailed error message from th
  }
});
}

</script>
<script>
      function showLoader2() {
    $('.loader-container').css('display', 'block');
}

function hideLoader2() {
    $('.loader-container').css('display', 'none');
}
  $( '#department').on('change', function(e) {
  var department = $('#department').val();
  var active = $('#active_users').val();
  var data = {
  "department": department,
  "active": active
};
console.log(data);
showLoader2();
$.ajax({
    url: '<?= base_url('attendance/get_users_by_department') ?>', // Replace with your actual API URL
    type: 'POST', // Adjust the request method if needed
    data: data,
    success: function(response) {
      hideLoader2();
      var data = JSON.parse(response);
      console.log(data);
      const select = $("#attendance_filter_user");
      // Clear previous options
      select.empty().append('<option value="">Select Users</option>');

      // Add new options based on the user data
      $.each(data, function(index, user) {
        const option = $('<option>')
          .val(user.employee_id) // Assuming each user has a unique "id" property
          .text(user.first_name+' '+user.last_name); // Assuming each user has a "name" property
          select.append(option);
      });
    },
    error: function(xhr, status, error) {
      // Handle any errors that occur during the AJAX request
      console.log('Error:', error);
    }
  });
  });
</script>

<script>
  // Assuming you have an HTML element with the ID "attendance_filter"
    $('#active_users').change(function() {
      // Get the selected value
      var selectedValue = $(this).val();
      
      // Perform an AJAX request based on the selected value
      $.ajax({
        url: '<?= base_url('attendance/get_active_inactive_users') ?>', // Replace with your actual API URL
        method: 'GET',             // Replace with the appropriate HTTP method
        data: { value: selectedValue },
        success: function(response) {
          var tableData = JSON.parse(response);
          // Handle the successful response from the server
          console.log(tableData);
          const select = $("#attendance_filter_user");
            // Clear previous options
            select.empty().append('<option value="">Select Users</option>');

            // Add new options based on the user data
            $.each(tableData, function(index, user) {
              const option = $('<option>')
                .val(user.employee_id) // Assuming each user has a unique "id" property
                .text(user.first_name+' '+user.last_name); // Assuming each user has a "name" property
                select.append(option);
            });
        },
        error: function(error) {
          // Handle errors if the request fails
          console.error(error);
        }
      });
    });
</script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<!-- <script>
$(document).ready( function () {
  var table = $('#attendance_list').DataTable( {
    paging: false
  });
} );
</script> -->

<script>
$('#filter').on('click',function(e){
  $('#attendance_list').bootstrapTable('refresh');
});

$(document).ready(function(){
  $('#from').daterangepicker({
    locale: {format: date_format_js},
    singleDatePicker: true,
  });

  $('#too').daterangepicker({
    locale: {format: date_format_js},
    singleDatePicker: true,
  });
});
</script>
<script>
function printAttendanceData() {
  var table = $('#attendance_list');
  var tbody = table.find('tbody');
  var attendanceData = [];

  var headerRow = table.find('thead tr');
  var headerValues = [];
  headerRow.find('th').each(function () {
    headerValues.push($(this).text().trim());
  });

  tbody.find('tr').each(function () {
    var rowData = [];
    $(this).find('td').each(function () {
      // Use .html() to preserve HTML markup
      rowData.push($(this).html());
    });
    attendanceData.push(rowData);
  });

  function splitArrayByType(arr) {
    const numericValues = [];
    const dateValues = [];
    const dayValues = [];

    for (const element of arr) {
      if (!isNaN(element)) {
        // Check if the element is a numeric value (not NaN) and not an empty string
        if (element !== '') {
          numericValues.push(element);
        }
      } else if (/^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s\d{4}$/.test(element)) {
        // Check if the element matches the pattern for a date (e.g., "Sep 2023")
        dateValues.push(element);
      } else if (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].includes(element)) {
        // Check if the element is a day name
        dayValues.push(element);
      }
    }
    numericValues.splice(0, 0, '', '', '');
    dayValues.splice(0, 0, 'S.no', 'Employee', 'A/HD/LM');
    return { numericValues, dateValues, dayValues };
  }

  const { numericValues, dateValues, dayValues } = splitArrayByType(headerValues);

  
  console.log(numericValues);
  console.log(dayValues);
  console.log(attendanceData);
  
 // Open a new window
var printWindow = window.open('', '', 'width=800,height=600');
printWindow.document.open();

// Create the HTML structure
printWindow.document.write('<html><head><title>Attendance List</title>');
printWindow.document.write('<style>');
printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
printWindow.document.write('th, td { border: 1px solid #ccc; padding: 8px; text-align: left; font-size: 9px; }');
printWindow.document.write('th { background-color: #f2f2f2; }');
printWindow.document.write('</style>');
printWindow.document.write('</head><body>');
printWindow.document.write('<h2>Attendance List</h2>');

// Check if there are more than 10 headings
if (dayValues.length > 15) {
    // Calculate how many tables are needed
    var numberOfTables = Math.ceil(dayValues.length / 15);
    for (var tableIndex = 0; tableIndex < numberOfTables; tableIndex++) {
        // Calculate the start and end indices for this table
        var startIndex = tableIndex * 15;
        var endIndex = Math.min(startIndex + 15, dayValues.length);

        // Create a table for this set of headings
        printWindow.document.write('<table border="1">');
        printWindow.document.write('<tr>');
        for (var i = startIndex; i < endIndex; i++) {
            printWindow.document.write('<th>' + numericValues[i] + '</th>');
        }
        printWindow.document.write('</tr>');
        printWindow.document.write('<tr>');
        for (var i = startIndex; i < endIndex; i++) {
            printWindow.document.write('<th>' + dayValues[i] + '</th>');
        }
        printWindow.document.write('</tr>');

        // Create the table body with attendance data for this set of headings
        for (var i = 0; i < attendanceData.length; i++) {
            printWindow.document.write('<tr>');
            for (var j = startIndex; j < endIndex; j++) {
                printWindow.document.write('<td>' + attendanceData[i][j] + '</td>');
            }
            printWindow.document.write('</tr>');
        }

        printWindow.document.write('</table>');

        // Add some space between tables
        if (tableIndex < numberOfTables - 1) {
            printWindow.document.write('<br><br><h4>Continue...</h4>');
        }
    }
} else {
    // If there are 10 or fewer headings, create a single table
    printWindow.document.write('<table border="1">');
    printWindow.document.write('<tr>');
    for (var i = 0; i < numericValues.length; i++) {
        printWindow.document.write('<th>' + numericValues[i] + '</th>');
    }
    printWindow.document.write('</tr>');
    printWindow.document.write('<tr>');
    for (var i = 0; i < dayValues.length; i++) {
        printWindow.document.write('<th>' + dayValues[i] + '</th>');
    }
    printWindow.document.write('</tr>');

    // Create the table body with attendance data
    for (var i = 0; i < attendanceData.length; i++) {
        printWindow.document.write('<tr>');
        for (var j = 0; j < dayValues.length; j++) {
            printWindow.document.write('<td>' + attendanceData[i][j] + '</td>');
        }
        printWindow.document.write('</tr>');
    }

    printWindow.document.write('</table>');
}

// Close the HTML document
printWindow.document.write('</body></html>');
printWindow.document.close();
printWindow.print();
printWindow.close();
}
</script>



</body>
</html>
