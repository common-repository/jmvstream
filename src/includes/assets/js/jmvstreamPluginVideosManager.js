function checkPage() {
    const page = window.location.href;
    if (page.includes('jmvstream-hub-videos') || page.includes('action=elementor') || page.includes('wp-admin/post.php?post=')) {
        return true;
    }
}

if (checkPage()) {

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

    function getHubVideos(title, page, gallery, orderBy, sort, initialDate, endDate, displayIn = 'plugin') {
        try {
            response = $.post({
                url: jmvstream.admin_url,
                dataType: "json",
                type: "POST",
                data: {
                    action: "getHubVideos",
                    title: title,
                    page: page,
                    gallery: gallery,
                    orderBy: orderBy,
                    initialDate: initialDate,
                    endDate: endDate,
                    sort: sort,
                },

                success: function (response) {
                    console.log(response);
                    response = response.data;
                    pagination(page, response.lastPage);
                    listVideos(response.videos, displayIn);
                },
                error: function (response) {
                    showMessage('error', "Error: " + response.statusText);
                }
            });

        } catch (error) {
            console.error(error);
        }
    }

    function addVideoToPlugin(hash, slug, title, player) {
        try {
            
            const response = $.ajax({
                url: jmvstream.admin_url,
                dataType: "json",
                type: "POST",
                data: {
                    action: "addVideoToPlugin",
                    hash: hash,
                    slug: slug,
                    title: title,
                    player: player,
                }
            });

            showMessage('success', `${jmvstream.translations.video_added_to_plugin}`);

            $videoAdded = getActionButton(true, hash);
            $(`#jmvstream__add-video-to-plugin-${hash}`).replaceWith($videoAdded);
            
            if ($(`#jmvstream__add-video-to-page-${hash}`).length) {
                $newAddToPageButton = getAddToPageButton(true, slug, hash);
                $(`#jmvstream__add-video-to-page-${hash}`).replaceWith($newAddToPageButton);
            }

            return;

        } catch (error) {
            console.error(error);
        }
    }

    function listHubGalleries() {
        $.getJSON(jmvstream.admin_url, {
            action: "getHubGalleries",
        }).done(function (galleries) {
            handleGalleriesSuccess(galleries);
        });
    }

    function handleGalleriesSuccess(galleries) {
        const $filterByGallery = $("#jmvstream__filter-by-gallery");

        $filterByGallery.append(`<option value="">${jmvstream.translations.all_galleries}</option>`);

        $.each(galleries, function (index, gallery) {
            $filterByGallery.append(`<option value="${gallery.uuid}">${gallery.name}</option>`);
        });
    }

    function listVideos(videos, displayIn = 'plugin') {

        const $listVideosContainer = $('.jmvstream__the-list');

        let $listVideos = '';
        $listVideosContainer.html("");

        if (displayIn == 'plugin') {
            $.each(videos, function (index, video) {

                let $duration = video.time.split('.')[0].split(':').slice(1).join(':');
                let $actionButton = getActionButton(video.in_plugin, video.hash, video.slug, video.title, video.playerSource);
                let getPreviewVideo = getPreviewOnClick(video.playerSource, video.title);

                $listVideos += `
                <tr class="jmvstream__list-row">
                    <td class="jmvstream__column-primary jmvstream__video-title" data-colname="${jmvstream.translations.title}">
                        <div>
                            <span class="jmvstream__thumbnail" ${getPreviewVideo}">
                                <img src="${video.thumbnail}" alt="${video.title}" loading="lazy">
                                <div class="jmvstream__preview-overlay">
                                    <div class="jmvstream__preview-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="56" fill="white" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </span>
                        </div>
        
                        <div class="jmvstream__video-info">
                            <strong class="has-media-icon">
                                <a class="jmvstream__video-title-list" ${getPreviewVideo}">
                                    ${video.title}
                                </a>
                            </strong>
                        </div>
                    </td>
                    <td class="parent jmvstream__column-parent jmvstream__column-duration" data-colname="Duration"> <p>${$duration}</p></td>
                    <td class="parent jmvstream__column-parent jmvstream__column-shortcode"  data-colname="Shortcode">
                        <div class="jmvstream__col-with-copy">    
                                ${iconClipboard}
                            <p> ${video.shortcode}</p>
                        </div>
                    </td>
                    <td class="date jmvstream__column-parent jmvstream__column-date" data-colname="Data">${video.created_date}</td>
                    <td class="action jmvstream__column-parent jmvstream__column-action" data-colname="Action">
                        <div class="jmvstream__action-buttons">
                            ${$actionButton}
                        </div>
                    </td>
                </tr>
                    `;

            });
        }

        if (displayIn == 'elementor') {

            $.each(videos, function (index, video) {

                let $actionButton = getActionButton(video.in_plugin, video.hash, video.slug, video.title, video.playerSource);
                let $addVideoToPageButton = getAddToPageButton(video.in_plugin, video.slug, video.hash);
                let $duration = video.time.split('.')[0].split(':').slice(1).join(':');
                let getPreviewVideo = getPreviewOnClick(video.playerSource, video.title);

                $listVideos += `
                    <tr class="jmvstream__list-row">
                        <td class="jmvstream__column-primary jmvstream__video-title" data-colname="${jmvstream.translations.title}">
                            <div>
                                <span class="jmvstream__thumbnail" ${getPreviewVideo}">
                                    <img src="${video.thumbnail}" alt="${video.title}" loading="lazy">
                                    <div class="jmvstream__preview-overlay">
                                        <div class="jmvstream__preview-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="white" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </span>
                            </div>
                            <div class="jmvstream__video-info">
                                <a class="jmvstream__video-title-list" ${getPreviewVideo}">
                                     ${video.title}
                                </a>
                            </div>
                        </td>
                        <td class="parent jmvstream__column-parent jmvstream__column-duration" data-colname="Duration"> <p>${$duration}</p></td>
                        <td class="date jmvstream__column-date jmvstream__column-parent column-date" data-colname="Data">${video.created_date}</td>
                        <td class="action jmvstream__column-parent jmvstream__column-action" data-colname="Action">
                            <div class="jmvstream__action-buttons">
                                ${$addVideoToPageButton}
                            </div>
                        </td>
                        <td class="action jmvstream__column-parent jmvstream__column-action" data-colname="Action">
                            <div class="jmvstream__action-buttons">
                                ${$actionButton}
                            </div>
                        </td>
                    </tr>
                    `;
            });
        }

        if (displayIn == 'gutenberg') {
            $.each(videos, function (index, video) {

                let $actionButton = getActionButton(video.in_plugin, video.hash, video.slug, video.title, video.playerSource);
                let $addVideoToPageButton = getAddToPageButton(video.in_plugin, video.slug, video.hash);
                let $duration = video.time.split('.')[0].split(':').slice(1).join(':');
                let getPreviewVideo = getPreviewOnClick(video.playerSource, video.title);

                $listVideos += `
                    <tr class="jmvstream__list-row">
                        <td class="jmvstream__column-primary jmvstream__video-title" data-colname="${jmvstream.translations.title}">
                            <div>
                                <span class="jmvstream__thumbnail" ${getPreviewVideo}>
                                    <img src="${video.thumbnail}" alt="${video.title}" loading="lazy">
                                    <div class="jmvstream__preview-overlay">
                                        <div class="jmvstream__preview-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="white" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </span>
                            </div>
                            <div class="jmvstream__video-info">
                                <a class="jmvstream__video-title-list" ${getPreviewVideo}>
                                     ${video.title}
                                </a>
                            </div>
                        </td>
                        <td class="parent jmvstream__column-parent jmvstream__column-duration" data-colname="Duration"> <p>${$duration}</p></td>
                        <td class="date jmvstream__column-date jmvstream__column-parent column-date" data-colname="Data">${video.created_date}</td>
                        <td class="action jmvstream__column-parent jmvstream__column-action" data-colname="Action">
                            <div class="jmvstream__action-buttons">
                                ${$addVideoToPageButton}
                            </div>
                        </td>
                        <td class="action jmvstream__column-parent jmvstream__column-action" data-colname="Action">
                            <div class="jmvstream__action-buttons">
                                ${$actionButton}
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        $listVideosContainer.append($listVideos);

        if (videos.length === 0) {
            $listVideosContainer.html(`<tr><td colspan= 4>${jmvstream.translations.no_videos_found}</td></tr>`);
        }
    }

    function getPreviewOnClick(player, title) {
        iframe = `<iframe allowfullscreen allow='autoplay; fullscreen' frameBorder='0' width='640' height='360' src='${player}'></iframe> `
        return `onclick="openPreviewModal('${player}', '${title}')"`;
    }

    function getActionButton(in_plugin, hash, slug, title, playerSource) {

        if (!in_plugin) {
            $addVideoButton = `<button id="jmvstream__add-video-to-plugin-${hash}" class="jmvstream__button jmvstream__add-video-button" onclick="addVideoToPlugin('${hash}', '${slug}', '${title}', '${playerSource}')">${jmvstream.translations.add_video}</button>`;
            return $addVideoButton;
        }
        
        if (in_plugin) {
            $videoAddedButton = `<button id="jmvstream__add-video-to-plugin-${hash}" class='jmvstream__button jmvstream__video-added-button' disabled>${jmvstream.translations.video_added}</button>`;
            return $videoAddedButton;
        }
    }

    function getAddToPageButton(in_plugin, slug, hash) {
        try {
            if (!in_plugin) {
                addVideoToPageButton = `<button id="jmvstream__add-video-to-page-${hash}" class="jmvstream__button jmvstream__add-video-to-page-button jmvstream__add-video-to-page-button-disabled" disabled>${jmvstream.translations.unavailable}</button>`;
                return addVideoToPageButton;
            }
            
            if (in_plugin) {
                addVideoToPageButton = `<button id="jmvstream__add-video-to-page-${hash}" class="jmvstream__button jmvstream__add-video-to-page-button" onclick="addVideoToPage('${slug}')">${jmvstream.translations.add_to_page}</button>`;
                return addVideoToPageButton;
            }
        } catch (error) {
            console.log("getAddToPageButton", error);
        }
    }

    function pagination(currentPage, lastPage) {
        const $paginator = $('#jmvstream__paginator');
        const $nextPage = $('#jmvstream__next-page');
        const $prevPage = $('#jmvstream__prev-page');

        // Esconde o paginator se não houver linhas
        if (lastPage === 1) {
            $paginator.hide();
            return;
        }

        if (lastPage > 1) {
            $paginator.show();
        }

        // Atualiza o botão "Anterior"
        if (currentPage === 1) {
            $prevPage
                .addClass('disabled')
                .hide();
        } else {
            $prevPage
                .removeClass('disabled')
                .show();
        }

        // Atualiza o botão "Próximo"
        if (currentPage === lastPage) {
            $nextPage
                .addClass('disabled')
                .hide();
        } else {
            $nextPage
                .removeClass('disabled')
                .show();
        }

        // Atualiza a página atual
        $('.jmvstream__current-page')
            .text(`${currentPage} ${jmvstream.translations.of} ${lastPage}`);
    }

    function showMessage(type, message) {
        $("#jmvstream__messages").html(`
                <div id="message" class="notice-${type} notice is-dismissible">
                    <p>${message}</p>
                    <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">${jmvstream.translations.dismiss_notice}</span>
                    </button>
                </div>`);
    }

    function openPreviewModal(urlPlayer, videoTitle) {
        const modal = `<div id="jmvstream__preview-modal-backdrop"><div>`;
        const frame = `<div id="jmvstream__preview-modal-player"></div>`;
        const closeModal = `      
            <button type="button" class="jmvstream__close-modal-icon" onclick="closePreviewModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white" class="bi bi-x" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
            `;
        const player = ` 
            <div id="jmvstream__preview-modal-header">
            <h3 class="jmvstream__preview-player-title">${videoTitle}</h3>
            </div>
            <div class="jmvstream__frame-player">
                
                <iframe allow="autoplay; fullscreen;" allowfullscreen class="jmvplayer" frameborder="0" src="${urlPlayer}"></iframe>
            </div>`;

        $("body").append(modal);
        $("#jmvstream__preview-modal-backdrop").html(frame);
        $("#jmvstream__preview-modal-player").append(player);
        $("#jmvstream__preview-modal-header").append(closeModal);

        $(document).on('keydown', function (event) {
            if (event.key == "Escape") {
                closePreviewModal();
            }
        });
    }

    function closePreviewModal() {
        $("#jmvstream__preview-modal-backdrop").remove();
    }

    function generateShortcode(slug) {
        let defaultWidth = jmvstream.shortcode.width;
        let defaultHeight = jmvstream.shortcode.height;
        let defaultAlign = jmvstream.shortcode.align;
        let shortcode = `[jmvstream video="${slug}" width="${defaultWidth}" height="${defaultHeight}" align="${defaultAlign}"]`;
        return shortcode;
    }

}
