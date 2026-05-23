private void button1ActionPerformed(java.awt.event.ActionEvent evt) {                                        
    
    javax.swing.table.DefaultTableModel model =
    (javax.swing.table.DefaultTableModel) table1.getModel();

    String id = id1.getText();
    String nama = nama2.getText();
    String member = jm1.getSelectedItem().toString();

    model.addRow(new Object[]{
        id,
        nama,
        member
    });

    id1.setText("");
    nama2.setText("");
    jm1.setSelectedIndex(0);
    
    }