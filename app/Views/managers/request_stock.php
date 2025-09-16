<?= $this->extend('layout') ?> 

<?= $this->section('title') ?>
Request Stock Item
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h2>Request Stock Item</h2>

<div class="table-container">
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Branch Name</th>
                <th>Item Name</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Message</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select class="form-select">
                        <option value="" disabled selected>Select</option>
                        <option value="branch1">Branch 1</option>
                        <option value="branch2">Branch 2</option>
                        <option value="branch3">Branch 3</option>
                        <option value="branch4">Branch 4</option>
                        <option value="branch5">Branch 5</option>
                    </select>
                </td>
                <td><input type="text" class="form-control" placeholder="Enter Item"></td>
                <td><input type="text" class="form-control" placeholder="Enter Type"></td>
                <td><input type="number" class="form-control" placeholder="0"></td>
                <td><input type="text" class="form-control" placeholder="Write message..."></td>
                <td><button class="btn btn-primary">Submit</button></td>
            </tr>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
