// $() will work as an alias for jQuery() inside of this function

var inputId = { id: '' };
const $ = jQuery;


function openGutenbergModal(id) {

    id = "jmvstream__" + id;
    var inputGutenbergId = inputId;
    inputGutenbergId.id = id;

    const modal = `<div id="jmvstream__media-modal-backdrop"><div>`;
    const frame = `<div id="jmvstream__media-modal-frame"></div>`;
    const titleFrame = `<h3 class="jmvstream__media-frame-title">${jmvstream.translations.jmvstream_videos}</h3>`;
    const mediaPage = `
        <div id="jmvstream__wrap" class="wrap">
            <div id = "jmvstream__messages"></div>
            <a href="https://hub.jmvtechnology.com/#/home" target="_blank" class="page-title-action">${jmvstream.translations.add_video}</a>
            <a href="${jmvstream.translations.plan_upgrade_url}" target="_blank" class="page-title-action">${jmvstream.translations.plan_upgrade}</a>
            <hr class="wp-header-end">
    
            <h2 class="screen-reader-text">${jmvstream.translations.filter_videos}</h2>
            <div class="wp-filter">
                <form method="post">
                    <div class="jmvstream__filter-container">
                        <div class="jmvstream__filter-item">
                            <label for="jmvstream__initial-date">${jmvstream.translations.initial_date}</label>
                            <input type="date" format="dd/mm/yyyy" id="jmvstream__initial-date" name="jmvstream__initial-date" placeholder="dd/mm/yyyy" />
                        </div>
                        <div class="jmvstream__filter-item">
                            <label for="jmvstream__end-date">${jmvstream.translations.end_date}</label>
                            <input type="date" format="dd/mm/yyyy" id="jmvstream__end-date" name="jmvstream__end-date" placeholder="dd/mm/yyyy" />
                        </div>
                    </div>
                </form>
                <form method="post">
                    <div class="jmvstream__filter-container jmvstream__search-form" id="search-videos">
                        <div class="jmvstream__filter-item">
                            <input id="jmvstream__filter-by-title" placeholder="${jmvstream.translations.search}" value="" type="text" />
                        </div>
                        <div class="jmvstream__filter-item">
                            <label for="attachment-filter" class="screen-reader-text"><?php esc_html_e('Filter by gallery', 'jmvstream') ?></label>
                            <select id="jmvstream__filter-by-gallery" class="attachment-filters" name="jmvstream__filter-by-gallery">
                                <!-- LISTAR LISTAR GALERIAS -->
                            </select>
                        </div>
                    </div>
                </form>  
            </div>
    
            <h2 class="screen-reader-text">${jmvstream.translations.list_videos}</h2>
            <table class="wp-list-table widefat striped table-view-list media">
                <thead>
                    <tr>
                        <th scope="col" class="jmvstream__col-header column-title column-primary sortable jmvstream__col-title">
                            <a id="jmvstream__sort-by-title" sort="">
                                <span>${jmvstream.translations.video}</span>
                            </a>
                        </th>
                        <th scope="col" class="jmvstream__col-header jmvstream__column-date">
                            <a>
                                <span>${jmvstream.translations.duration ?? "Duration"}</span>
                            </a>
                        </th>
                        <th scope="col" class="jmvstream__col-header jmvstream__column-date sortable">
                            <a id="jmvstream__sort-by-date" sort="">
                                <span>${jmvstream.translations.date}</span>
                            </a>
                        </th>
                        <th scope="col" class="jmvstream__col-header jmvstream__column-date">
                            <a>
                                <span>${jmvstream.translations.add_page ?? "Adicionar à Página"}</span>
                            </a>
                        </th>
                        <th scope="col" class="jmvstream__col-header jmvstream__column-date">
                            <a>
                                <span>${jmvstream.translations.add_plugin ?? "Adicionar ao plugin"}</span>
                            </a>
                        </th>
                    </tr>
                </thead>
    
                <tbody id="the-list" class="jmvstream__the-list">
                    <!-- LISTA DOS VIDEOS -->
                </tbody>
    
            </table>
            <div id="jmvstream__paginator" class="tablenav bottom">
                <div class="jmvstream__tablenav-pages">
                    <div class="jmvstream__displaying-num">
                        <span></span>
                    </div>
                    <div class="jmvstream__pagination">
                        <span id="jmvstream__prev-page" class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                        <span class="screen-reader-text">${jmvstream.translations.current_page}</span>
                        <span id="jmvstream__table-paging" class="paging-input">
                            <span class="jmvstream__current-page tablenav-paging-text"></span>
                        </span>
                        <span id="jmvstream__next-page" class="tablenav-pages-navspan button" aria-hidden="true">›</span></a>
                    </div>
                </div>

                <br class="clear">
            </div>
        </div>
    
        <div class="clear"></div>
        `;
    const closeModalButton = ` 
            <button type="button" class="media-modal-close" onclick="closeModal()">
                <span class="media-modal-icon">
                    <span class="screen-reader-text">Fechar janela
                    </span>
                </span>
            </button>
        `;

    $("body").append(modal);
    $("#jmvstream__media-modal-backdrop").html(frame);
    $("#jmvstream__media-modal-frame").append(titleFrame);
    $("#jmvstream__media-modal-frame").append(mediaPage);
    $("#jmvstream__media-modal-frame").append(closeModalButton);

    $(document).on('keydown', function (event) {
        if (event.key == "Escape") {
            closeModal();
        }
    });

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

        getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'gutenberg');

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
                getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'gutenberg');
            }, 1000);
        });

        $initialDate.on('change', function (e) {
            e.preventDefault();
            page = 1;
            initialDate = $(this).val();
            console.log(initialDate);
            getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'gutenberg');
        });

        $endDate.on('change', function (e) {
            e.preventDefault();
            page = 1;
            endDate = $(this).val();
            console.log(endDate);
            getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'gutenberg');
        });

        $filterByGallery.on('change', function (e) {
            e.preventDefault();
            gallery = $(this).val();
            page = 1;
            getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'gutenberg');
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
                $sortByTitle.parent().removeClass('desc').addClass('asc');
            }

            getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'gutenberg');

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

            getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'gutenberg');

        });

        nextPage.on('click', function (e) {
            e.preventDefault();
            page++;
            getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'gutenberg');
        });

        prevPage.on('click', function (e) {
            e.preventDefault();
            page--;
            getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, 'gutenberg');
        });
    });

}

function closeModal() {
    $('#jmvstream__media-modal-backdrop').fadeOut().remove();
}

function addVideoToPage(slug) {
    let value = generateShortcode(slug);

    var input = document.getElementById(inputId.id);
    var setValue = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'value').set;
    setValue.call(input, [value]);
    var e = new Event('input', { bubbles: true });
    input.dispatchEvent(e);
    closeModal();
    return;
}

