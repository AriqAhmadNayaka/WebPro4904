<?php $this->load->view('crudjs/header'); ?>

<div class="container">
    <div class="nav">
        <ul>
            <li><a href="<?php echo base_url(); ?>">Home</a></li>
            <li><a href="<?php echo base_url('crudjs'); ?>">CRUD with AJAX</a></li>
        </ul>
    </div>

    <div id="alertContainer">
        <div id="successAlert" class="alert alert-success"></div>
        <div id="errorAlert" class="alert alert-error"></div>
    </div>

    <div class="card">
        <div class="header-actions">
            <h2>All Records</h2>
            <button type="button" class="btn btn-success" onclick="openCreateModal()">+ Create New Record</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 100px;">Image</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th style="width: 250px;">Article Preview</th>
                    <th style="width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('crudjs/footer'); ?>