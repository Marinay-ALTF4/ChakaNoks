<?= $this->include('template/header') ?>  
<div class="content">
    <?php $success = session()->getFlashdata('success'); ?>
    <?php if (is_scalar($success) && $success !== ''): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc((string) $success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php $error = session()->getFlashdata('error'); ?>
    <?php if (is_scalar($error) && $error !== ''): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= esc((string) $error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php $warning = session()->getFlashdata('warning'); ?>
    <?php if (is_scalar($warning) && $warning !== ''): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= esc((string) $warning) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php $info = session()->getFlashdata('info'); ?>
    <?php if (is_scalar($info) && $info !== ''): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= esc((string) $info) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?= $this->renderSection('content') ?>
</div>
<?= $this->include('template/footer') ?>
