/**
 * Visited Articles Tracker
 * ----------------------------------------------------------------
 * Menandai artikel yang sudah pernah dibuka pengguna dengan
 * menyimpan slug-nya di localStorage. Pendekatan opt-in: hanya
 * elemen ber-atribut [data-article-link="<slug>"] yang dilacak.
 *
 * Mengapa opt-in?
 *   - Aman dari false-positive (mis. link nav, footer, sosial media).
 *   - Tidak bergantung struktur URL — slug dipasang eksplisit di Blade.
 *   - Performa: tidak perlu memindai seluruh <a> di dokumen.
 *
 * Mengapa localStorage, bukan :visited?
 *   - :visited di CSS dibatasi browser (hanya color/background-color/
 *     border-color, dengan computed value harus sama dengan unvisited).
 *   - localStorage membuat state visited konsisten di semua kartu artikel.
 *
 * Penggunaan di Blade:
 *   <article class="news-list-card" data-article-link="{{ $berita->slug }}">
 *     ...
 *     <a href="{{ route('berita.show', $berita->slug) }}"
 *        data-article-link="{{ $berita->slug }}">...</a>
 *   </article>
 * ---------------------------------------------------------------- */
(function () {
    "use strict";

    var STORAGE_KEY = "rk:visited-articles";
    var MAX_ENTRIES = 100;

    /** Baca daftar slug yang sudah dikunjungi dari localStorage. */
    function readVisited() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return [];
            var parsed = JSON.parse(raw);
            return Array.isArray(parsed)
                ? parsed.filter(function (s) { return typeof s === "string"; })
                : [];
        } catch (e) {
            return [];
        }
    }

    /** Simpan daftar slug ke localStorage (cap MAX_ENTRIES). */
    function writeVisited(list) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(list.slice(0, MAX_ENTRIES)));
        } catch (e) {
            /* Quota / private mode → diam saja, fitur tetap berjalan untuk session. */
        }
    }

    /** Tambah slug ke daftar visited (most-recent first). */
    function rememberSlug(slug) {
        var list = readVisited().filter(function (s) { return s !== slug; });
        list.unshift(slug);
        writeVisited(list);
    }

    /** Tandai semua kartu/link yang slug-nya ada di set `visited`. */
    function applyVisitedState(visited) {
        var elements = document.querySelectorAll("[data-article-link]");
        elements.forEach(function (el) {
            var slug = el.getAttribute("data-article-link");
            if (!slug || !visited.has(slug)) return;

            el.setAttribute("data-visited", "true");

            // Update aria-label pada link agar screen reader tahu sudah dibaca.
            if (el.tagName === "A" && !el.dataset.visitedAriaApplied) {
                var current = el.getAttribute("aria-label") || el.textContent.trim();
                el.setAttribute("aria-label", current + " (sudah dibaca)");
                el.dataset.visitedAriaApplied = "1";
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        var visitedSet = new Set(readVisited());
        applyVisitedState(visitedSet);

        // Delegasi event: rekam saat user klik link artikel.
        document.addEventListener("click", function (event) {
            var link = event.target.closest("a[data-article-link]");
            if (!link) return;
            var slug = link.getAttribute("data-article-link");
            if (!slug) return;
            rememberSlug(slug);
            visitedSet.add(slug);
            // Apply langsung — supaya saat user kembali via tombol back, state sudah konsisten.
            applyVisitedState(visitedSet);
        }, { capture: true });
    });
})();
