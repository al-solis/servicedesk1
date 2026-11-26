<script>
    document.addEventListener('DOMContentLoaded', function() {
        const MAX_MB = 10;
        const MAX_BYTES = MAX_MB * 1024 * 1024;
        const ALLOWED = {
            'images[]': ['jpeg', 'jpg', 'png', 'gif'],
            'files[]': ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'zip', 'rar'],
        };

        document.querySelectorAll('input[type="file"][name="images[]"], input[type="file"][name="files[]"]')
            .forEach(function(input) {
                const allowed = ALLOWED[input.name];
                const box = document.createElement('div');
                box.className = 'hidden mt-1 text-xs text-red-600';
                box.setAttribute('role', 'alert');
                (input.closest('.relative') || input).insertAdjacentElement('afterend', box);

                input.addEventListener('change', function() {
                    box.replaceChildren();
                    box.classList.add('hidden');

                    const keep = new DataTransfer();
                    const problems = [];

                    Array.from(input.files).forEach(function(file) {
                        const ext = file.name.split('.').pop().toLowerCase();
                        if (!allowed.includes(ext)) {
                            problems.push('"' + file.name +
                                '" is not an allowed file type (allowed: .' + allowed
                                .join(', .') + ').');
                        } else if (file.size > MAX_BYTES) {
                            problems.push('"' + file.name + '" is ' + (file.size / 1048576)
                                .toFixed(1) +
                                ' MB. The maximum is ' + MAX_MB + ' MB per file.');
                        } else {
                            keep.items.add(file);
                        }
                    });

                    if (problems.length) {
                        input.files = keep.files; // drop only the rejected files
                        problems.forEach(function(msg) {
                            const p = document.createElement('p');
                            p.textContent =
                            msg; // textContent, so filenames can't inject HTML
                            box.appendChild(p);
                        });
                        box.classList.remove('hidden');
                    }
                });
            });
    });
</script>
