

function checkPluginPage() {
    const page = window.location.href;
    if (page.includes('jmvstream-hub-videos')) {
        return true;
    }
}

if (checkPluginPage()) {
    const $ = jQuery;
    const { __, _x, _n, sprintf } = wp.i18n;

    const iconCopied = `
    <svg id="jmvstream__icon-copied" class="jmvstream__copy-icon " xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-clipboard-check" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
        <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
    </svg>`;

    const iconClipboard = `
    <svg id="jmvstream__icon-clipboard" class="jmvstream__copy-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
        <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
    </svg>`;

    const videoAddedButton = `<button class='jmvstream__video-added-button' disabled>${jmvstream.translations.video_added}</button>`;

    $(document).ready(function () {

        listHubGalleries();

        $(function () {

            const $filterByTitle = $('#jmvstream__filter-by-title');
            const $filterByGallery = $('#jmvstream__filter-by-gallery');

            const $sortByTitle = $('#jmvstream__sort-by-title');
            const $sortByDate = $('#jmvstream__sort-by-date');

            const $initialDate = $('#jmvstream__initial-date');
            const $endDate = $('#jmvstream__end-date');

            const prevPage = $('#jmvstream__prev-page');
            const nextPage = $('#jmvstream__next-page');

            let page = 1;
            let title = $filterByTitle.val();
            let gallery = $filterByGallery.val();
            let initialDate = $initialDate.val();
            let endDate = $endDate.val();
            let orderBy = 'created_date';
            let sort = 'DESC';

            getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'plugin');

            $filterByTitle.keydown(function (e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    return false;
                }
            });

            let timer;
            $filterByTitle.keyup(function (e) {
                e.preventDefault();
                title = $(this).val();
                page = 1;
                clearTimeout(timer); // cancela o temporizador anterior
                timer = setTimeout(function () {
                    getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'plugin');
                }, 1000); 
            });

            $initialDate.on('change', function (e) {
                e.preventDefault();
                page = 1;
                initialDate = $(this).val();
                console.log(initialDate);
                getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'plugin');
            });

            $endDate.on('change', function (e) {
                e.preventDefault();
                page = 1;
                endDate = $(this).val();
                console.log(endDate);
                getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'plugin');
            });

            $filterByGallery.on('change', function (e) {
                e.preventDefault();
                gallery = $(this).val();
                page = 1;
                getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'plugin');
            });

            $sortByTitle.on('click', function (e) {
                e.preventDefault();
                orderBy = 'name';
                sort = $(this).attr('sort') || 'asc';
                page = 1;

                if (sort == 'asc') {
                    sort = 'desc';
                    $sortByTitle.attr('sort', 'desc');
                    $sortByTitle.parent().removeClass('asc').addClass('desc');
                } else if (sort == 'desc') {
                    sort = 'asc';
                    $sortByTitle.attr('sort', 'asc');
                    $sortByTitle.parent().removeClass('asc').addClass('asc');
                }

                getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'plugin');

            });

            $sortByDate.on('click', function (e) {
                e.preventDefault();
                orderBy = 'created_date';
                sort = $(this).attr('sort') || 'asc';
                page = 1;

                if (sort == 'asc') {
                    sort = 'desc';
                    $sortByDate.attr('sort', 'desc');
                    $sortByDate.parent().removeClass('desc').addClass('asc');
                } else if (sort == 'desc') {
                    sort = 'asc';
                    $sortByDate.attr('sort', 'asc');
                    $sortByDate.parent().removeClass('asc').addClass('desc');
                }

                getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'plugin');

            });

            nextPage.on('click', function (e) {
                e.preventDefault();
                page++;
                getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'plugin');
            });

            prevPage.on('click', function (e) {
                e.preventDefault();
                page--;
                getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'plugin');
            });

        });
    });

    $(document).click(function (e) {
        if ($(e.target).hasClass('jmvstream__copy-icon')) {
            element = $(e.target);
            var copied = element.next().text();
            navigator.clipboard.writeText(copied);
            $(element).replaceWith(iconCopied);

            setTimeout(() => {
                $('#jmvstream__icon-copied').replaceWith(iconClipboard);
            }, 3000);
        };
    });

    $(document).click(function (e) {
        if ($(e.target).hasClass('jmvstream__add-video-button')) {
            element = $(e.target);
            $(element).replaceWith(videoAddedButton);
        };
    });
}
