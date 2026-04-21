<!-- Export Scripts - Include in admin list pages -->
<script src="app/assets/jspdf.umd.min.js"></script>
<script src="app/assets/jspdf.plugin.autotable.min.js"></script>
<script src="app/assets/xlsx.full.min.js"></script>

<script>
function exportToPDF(tableId, filename) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Get table data
    const table = document.getElementById(tableId);
    const rows = [];
    
    // Get headers
    const headers = [];
    table.querySelectorAll('thead th').forEach(th => {
        headers.push(th.innerText);
    });
    
    // Get body rows
    table.querySelectorAll('tbody tr').forEach(tr => {
        const row = [];
        tr.querySelectorAll('td').forEach((td, index) => {
            // Skip action column (last column)
            if (index < tr.querySelectorAll('td').length - 1) {
                row.push(td.innerText);
            }
        });
        rows.push(row);
    });
    
    // Add title
    doc.text(filename.replace('.pdf', '').toUpperCase(), 14, 15);
    
    // Add table
    doc.autoTable({
        head: [headers.slice(0, -1)], // Remove action column from header
        body: rows,
        startY: 25
    });
    
    doc.save(filename);
}

function exportToExcel(tableId, filename) {
    const table = document.getElementById(tableId);
    const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
    
    // Remove action column
    const ws = wb.Sheets["Sheet1"];
    const range = XLSX.utils.decode_range(ws['!ref']);
    range.e.c--; // Remove last column
    ws['!ref'] = XLSX.utils.encode_range(range);
    
    XLSX.writeFile(wb, filename);
}
</script>
