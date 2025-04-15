</div>
</main>
</div>

<?php if (session()->has('error')): ?>
    <div class="position-fixed start-50 translate-middle-x d-block z-3 alert alert-danger">
        <?= session('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->has('success')): ?>
    <div class="position-fixed start-50 translate-middle-x d-block z-3 alert alert-success">
        <?= session('success') ?>
    </div>
<?php endif; ?>


<script src="<?= base_url('js/script.js') ?>"></script>
<script src="<?= base_url('js/font-awesome.js') ?>"></script>
<script src="<?= base_url('js/simple-datatable.js') ?>"></script>
</body>

</html>