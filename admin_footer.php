</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        if (document.querySelector('textarea[name="post_content"]')) {
            ClassicEditor
                .create(document.querySelector('textarea[name="post_content"]'))
                .catch(error => {
                    console.error(error);
                });
        }
    </script>
</body>
</html>