<?php $this->load->view('includes/head'); ?>
<style>
    .hidden{
    display: none;
  }
    /* Target all cells */
.table th {
  font-size: 12px;
}

#attendance_list {
  table-layout: auto;
  width: auto;
  max-width: 100%;
}

.th-inner{
  padding:0 !important;
}

#attendance_list th, #attendance_list td {
  white-space: nowrap;
}

.table td {
  /* Your styling here */
  font-size: 10px;
}
.green-background{
  background-color: #efffc7 !important; /* Remove background color */
}
.nopad{
  padding:0 !important;
}
.nobold{
  font-weight: normal;
}
.no-background{
  background-color: #fff !important; /* Remove background color */
}
.left-pad{
  padding-left:0.4em !important;
  padding-right:0.4em !important;
}

.color-background{
  background-color: #f5f5f5 !important; /* Remove background color */
}

.bold{
  font-weight: bold;
  font-size: 11px  !important ;
}
.transparent-cell {
    background-color: transparent !important ;
}

.single-line-text {
  white-space: nowrap;
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
              <?php
                if($this->ion_auth->is_admin() || permissions('attendance_view_all')){ ?>
                  <?=$this->lang->line('attendance')?htmlspecialchars($this->lang->line('attendance')):'User Attendance - '?> 
                  <?= htmlspecialchars($name) ?>
                  <?php
                }else{?>
                  <?=$this->lang->line('attendance')?htmlspecialchars($this->lang->line('attendance')):'Attendance'?> 
                  <div class="btn-group">
                    <a href="#" class="btn btn-sm btn-primary"><?=$this->lang->line('report')?htmlspecialchars($this->lang->line('report')):'Report View'?></a>
                    <a href="<?=base_url('attendance')?>" class="btn btn-sm "><?=$this->lang->line('list_view')?htmlspecialchars($this->lang->line('list_view')):'List View'?></a>
                  </div>
                  <?php
                    }
              ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item active"><a href="<?=base_url().'/attendance'?>"><?=$this->lang->line('attendance')?$this->lang->line('attendance'):'Attendance'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('user-Attendance')?htmlspecialchars($this->lang->line('user-Attendance')):'User Attendance'?></div>
            </div>
          </div>
          <div class="section-body">
            
            <div class="row">
                <div class="form-group col-md-12">
                <select class="form-control select2" name="filter" id="attendance_filter" onchange="updateDivContent()">
                  <option value="today"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Today'?></option>
                  <option value="ystdy"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Yesterday'?></option>
                  <option value="tweek"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Week'?></option>
                  <option value="lweek"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Last Week'?></option>
                  <option value="tmonth" selected><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Month'?></option>
                  <option value="lmonth"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Last Month'?></option>
                  <option value="tyear"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'This Year'?></option>
                  <option value="lyear"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Last Year'?></option>
                  <option value="custom"><?=$this->lang->line('select_filter')?$this->lang->line('select_users'):'Custom'?></option>
                </select>
              </div>
                <div id="myDiv" class="form-group col-md-6 hidden">
                  <input type="text" name="from" id="from" class="form-control">
                </div>
                <div id="myDiv2" class="form-group col-md-6 hidden">
                  <input type="text" name="too" id="too" class="form-control">
                </div>
              
            </div>
            <div class="toolbar" id="toolbar">
                <button class="btn btn-secondary" onclick="printAttendanceData()">Download Pdf</button>
            </div>
           <div class="row">
                <div class="col-md-12">
                  <div class="card card-primary">
                    <div class="card-body"> 
                    <table id="attendance_list" class="table-striped"
                          data-toggle="table"
                          data-click-to-select="true"
                          data-pagination="false"
                          data-show-columns="false"
                          data-search="false" 
                          data-show-refresh="false"
                          data-trim-on-search="false"
                          data-sort-name="user"
                          data-sort-order="desc"
                          data-mobile-responsive="true"
                          data-toolbar="#toolbar"
                          data-show-export="true" 
                          data-export-types="['json', 'csv', 'txt', 'sql', 'doc', 'excel']" 
                          data-maintain-selected="true"
                          data-query-params="queryParams">
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

function updateTable(response) {
  var tableData = JSON.parse(response);

  function formatDate(date) {
    var parsedDate = new Date(date);
    var formattedDate = parsedDate.toLocaleDateString('en-US', {
      day: 'numeric',
      month: 'short'
    });
    return formattedDate;
  }
  // Replace the table header with the new headings
  var tableHead = $('#attendance_list thead');
  tableHead.empty(); // Clear existing table header

  if (!tableData.headings || tableData.headings.length === 0) {
    // If no headings, display a single cell with "No Matching Records Found"
    var noRecordsRow = '<tr><td class="bold" colspan="2"><center>No Matching Records Found</center></td></tr>';
    tableHead.append(noRecordsRow);

    // Clear the table body as well
    var tableBody = $('#attendance_list tbody');
    tableBody.empty();

    return;
  }

  var headerRow = '<tr>' +
    '<th class="nopad transparent-cell">&nbsp;</th>';
    
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

  if (tableData.graceDays) {
    headerRow += '<th class="left-pad bold" style="background-color: #efffc7;">Grace Mins<br>Applied</th>';
  } else {
    headerRow += '<th class="nopad transparent-cell">&nbsp;</th>';
  }

  headerRow += '</tr>'; // Close the first header row

  // Add the second header row for days
  var daysRow = '<tr>' +
    '<th class="left-pad">Days</th>';

  $.each(tableData.headings, function (index, date) {
    var day = new Date(date).toLocaleDateString('en-US', { weekday: 'short' });
    daysRow += '<th class="left-pad nobold transparent-cell">' + day + '</th>';
  });

  daysRow += '<th class="left-pad" rowspan="2" data-field="apl">Absent<br>/Half Day<br>/Late Min</th>' +
    '</tr>'; // Close the second header row

  // Add the third header row for dates
  var datesRow = '<tr>' +
    '<th class="left-pad">Date</th>';

    $.each(tableData.headings, function (index, date) {
    var parts = new Date(date).toLocaleDateString('en-US', {
        day: 'numeric',
        month: 'short'
    }).split(' ');
    
    var formattedDate = parts[1] + ' ' + parts[0];

    // Check if the current date is present in tableData.lateMinutesDates
    if (tableData.graceDays && tableData.graceDates && tableData.graceDates.includes(date)) {
      datesRow += '<th class="left-pad nobold " style="background-color: #efffc7;">' + formattedDate + '</th>';
    } else {
      datesRow += '<th class="left-pad nobold transparent-cell">' + formattedDate + '</th>';
    }
  });

  // Combine all header rows and append to the table head
  tableHead.append(headerRow + daysRow + datesRow);

  // Replace the table body with the new data
  var tableBody = $('#attendance_list tbody');
  tableBody.empty(); // Clear existing table rows

  // Add the rows for each user
  $.each(tableData.rows, function (index, userRow) {
    // Add the attendance data for each date - 4th row (Check In)
    var checkInRow = '<tr>' +
      '<td class="left-pad bold color-background">Check In<br>/Check Out</td>';

    $.each(tableData.headings, function (index, date) {
      checkInRow += '<td class="left-pad no-background single-line-text">' + (userRow[date] ? userRow[date] : '') + '</td>';
    });
                                      
    checkInRow += '<td class="left-pad no-background" rowspan="2" > ' + tableData.apl + '</td>';                            

    checkInRow += '</tr>'; // Close the 4th row (Check In)

    // Add the attendance data for each date - 5th row (Status)
    var statusRow = '<tr>' +
      '<td class="left-pad bold color-background">Status</td>';

    $.each(tableData.headings, function (index, date) {
      statusRow += '<td class="left-pad">' + (userRow[userRow[date]] ? userRow[userRow[date]] : '') + '</td>';
    });

    statusRow += '</tr>'; // Close the 5th row (Status)

    // Append both rows to the table body
    tableBody.append(checkInRow + statusRow);
  });
}

$(document).ready(function(){
  var currentDate = new Date();
  var firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);

  $('#from').daterangepicker({
    locale: { format: date_format_js },
    singleDatePicker: true,
    startDate: firstDayOfMonth,
    maxDate: moment().startOf('day') // Set minimum date to today
  });

  $('#too').daterangepicker({
    locale: {format: date_format_js},
    singleDatePicker: true,
    maxDate: moment().startOf('day') // Set minimum date to today
  });

  var currentUrl = window.location.href;
  var id;

  if (currentUrl.match(/\/attendance\/user_attendance\/\d+(?:#|$)/)) {
    id = currentUrl.match(/\/(\d+)(?:#|$)/)[1];
  } else if (currentUrl.match(/\/attendance\/user_attendance(?:#|$)/)) {
    id = <?= json_encode($user_id) ?>;
  }
  // Define a function to handle the AJAX request
  function handleAjaxRequest() {
    var data = {
      "user_id" : id,
      "filter": $('#attendance_filter').val(),
      "from": $('#from').val(),
      "too": $('#too').val()
    };
    showLoader();

    // Make an AJAX request to the controller function
    $.ajax({
      type: 'POST',
      url: '<?=base_url()?>attendance/get_attendance_report3', 
      data: data,
      success: function(response) {
        console.log(response);
        hideLoader();
        var tableData = JSON.parse(response);
        // Update the table content with the received data
        updateTable(response);
      },
      error: function(xhr, status, error) {
        // Handle any errors here
        console.log('AJAX Error: ' + status + ' - ' + error);
      }
    });
  }

  
  // Call the function when the page loads
  handleAjaxRequest();

  // Call the function when the filter elements change
  $('#from, #too, #attendance_filter').change(function() {
    handleAjaxRequest();
  });

  // Initialize Bootstrap Table Export extension with custom format
  $('#attendance_list').bootstrapTable({
        exportDataType: 'all', // Export all data, including the hidden columns
        exportOptions: {
            fileName: 'Attendance_Report_<?= $user_name ?>', // File name for the exported file
            preventInjection: false, // Allow using HTML and CSS in the exported table
            formatNoMatches: function() {
                // Custom function to switch rows with columns
                var table = $('#attendance_list').bootstrapTable('getData');
                var columns = [];
                var rows = [];

                // Extract columns and rows from the table data
                for (var i = 0; i < table.length; i++) {
                    var row = table[i];
                    for (var key in row) {
                        if (!columns.includes(key)) {
                            columns.push(key);
                        }
                    }
                }

                // Generate rows for the exported table
                for (var i = 0; i < columns.length; i++) {
                    var newRow = {};
                    newRow[' '] = columns[i]; // Use a blank space as the first column header
                    for (var j = 0; j < table.length; j++) {
                        var row = table[j];
                        newRow[row['Day']] = row[columns[i]];
                    }
                    rows.push(newRow);
                }
                return JSON.stringify(rows);
            }
        }
    });
});

function updateDivContent() {
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
}

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
// Filter out values like "Jan 2023" or empty strings
      headerValues = headerValues.filter(function (value) {
        return !/^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s\d{4}$/.test(value) && value !== "";
      });
    tbody.find('tr').each(function () {
        var rowData = [];
        $(this).find('td').each(function () {
            // Use .html() to preserve HTML markup
            rowData.push($(this).html());
        });
        attendanceData.push(rowData);
    });
    
    var days = [];
    var dates = [];
    var absentHeader = [];

    var isDays = true;
    var isDates = false;
    var isAbsentHeader = false;

    for (var i = 0; i < headerValues.length; i++) {
        var header = headerValues[i];
        if (header === "Date") {
            isDays = false;
            isDates = true;
            isAbsentHeader = false;
        } else if (header === "Absent/Half Day/Late Min") {
            isDays = false;
            isDates = false;
            isAbsentHeader = true;
        }
        if (isDays) {
            days.push(header);
        } else if (isDates) {
            dates.push(header);
        } else if (isAbsentHeader) {
            absentHeader.push(header);
        }
    }

    var searchString = /0\/<br>0\/<br>\d+ mins/; // Use a regular expression pattern

    var indexesToRemove = [];

    for (var i = 0; i < attendanceData.length; i++) {
        for (var j = 0; j < attendanceData[i].length; j++) {
            if (searchString.test(attendanceData[i][j])) {
                absentHeader.push(attendanceData[i][j]);
                indexesToRemove.push({ row: i, col: j });
            }
        }
    }

    // Remove the found entries from attendanceData
    for (var k = indexesToRemove.length - 1; k >= 0; k--) {
        var rowIndex = indexesToRemove[k].row;
        var colIndex = indexesToRemove[k].col;
        attendanceData[rowIndex].splice(colIndex, 1);
    }
    
    // Split the tables into chunks of 10 columns
    var chunkSize = 12;
    var numChunks = Math.ceil(headerValues.length / chunkSize);

    var printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.open();
    printWindow.document.write('<html><head><title>Attendance List</title>');
    
    // Add CSS styles
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
    printWindow.document.write('th, td { border: 1px solid #ccc; padding: 8px; text-align: left;font-size: 9px; }');
    printWindow.document.write('th { background-color: #f2f2f2; }');
    printWindow.document.write('</style>');
    
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h2>Attendance List</h2>');
    printWindow.document.write('<h4><?=$name?></h4>');

    // Print the table headers
    for (var chunk = 0; chunk < numChunks; chunk++) {
        var startCol = chunk * chunkSize;
        var endCol = startCol + chunkSize;

        printWindow.document.write('<table border="1">');
        printWindow.document.write('<thead>');

        // Print the "Days" header row
        printWindow.document.write('<tr>');
        for (var i = startCol; i < endCol && i < days.length; i++) {
            printWindow.document.write('<th>' + days[i] + '</th>');
        }
        printWindow.document.write('</tr>');

        // Print the "Dates" header row
        printWindow.document.write('<tr>');
        for (var j = startCol; j < endCol && j < dates.length; j++) {
            printWindow.document.write('<th>' + dates[j] + '</th>');
        }
        printWindow.document.write('</tr>');

        printWindow.document.write('</thead>');

        // Print the data rows
        printWindow.document.write('<tbody>');
        for (var row = 0; row < attendanceData.length; row++) {
            printWindow.document.write('<tr>');
            for (var col = startCol; col < endCol && col < attendanceData[row].length; col++) {
                printWindow.document.write('<td>' + attendanceData[row][col] + '</td>');
            }
            printWindow.document.write('</tr>');
        }
        printWindow.document.write('</tbody>');
        printWindow.document.write('</table>');

        printWindow.document.write('<br>');
        printWindow.document.write('<br>');
        printWindow.document.write('<br>');
    }

    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
}
</script>
</body>
</html>
