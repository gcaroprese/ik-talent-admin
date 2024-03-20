function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob(["\uFEFF"+csv], {type: 'text/csv; charset=utf-18'});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}
function exportTableToCSV_additional_fields(filename) {
    var csv = [];
    var rows = document.querySelectorAll("#table_export tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        
        csv.push(row.join("\t"));        
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}
function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("#ik_talent_existing tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td.export_enabled, th.export_enabled");
        
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        
        csv.push(row.join("\t"));        
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
}