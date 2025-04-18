</div>
</main>
</div>

<?php if (session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show position-fixed start-50 translate-middle-x mt-3"
    style="top: 3em" role="alert">
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show position-fixed start-50 translate-middle-x mt-3"
    style="top: 3em" role="alert">
        <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<script src="<?= base_url('js/script.js') ?>"></script>
<script src="<?= base_url('js/font-awesome.js') ?>"></script>
<script src="<?= base_url('js/simple-datatable.js') ?>"></script>
</body>

</html>