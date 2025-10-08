<?php
$title = "Панель управления";
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    http_response_code(403);
    die('Access denied');
}

?>
<div class="dashboard-container">
    <div class="tasks-section">
        <div class="card">
            <div class="card-header">
                <h2>Данные задач</h2>
                <button id="refreshBtn" class="btn-refresh">
                    <span class="refresh-icon">↻</span> Обновить
                </button>
            </div>
            <div class="card-body">
                <div id="loadingSpinner" class="loading-spinner d-none">
                    <div class="spinner"></div>
                </div>

                <div id="errorAlert" class="alert error-alert d-none"></div>

                <div class="filters-container">
                    <select id="statusFilter" class="filter-select">
                        <option value="">Все статусы</option>
                    </select>

                    <select id="sortOrder" class="filter-select">
                        <option value="newest">Сначала новые</option>
                        <option value="oldest">Сначала старые</option>
                    </select>

                    <button id="applyFilters" class="btn-primary">Применить</button>
                </div>

                <div class="table-container">
                    <table class="tasks-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Имя пользователя</th>
                            <th>Статус</th>
                            <th>Теги</th>
                            <th>Заголовок</th>
                            <th>Описание</th>
                            <th>Ответ администратора</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody id="tableBody">
                        </tbody>
                    </table>
                </div>

                <div id="emptyMessage" class="alert info-alert text-center d-none">
                    Нет данных для отображения
                </div>
            </div>
        </div>
    </div>

    <div id="viewTaskModal" class="modal d-none">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Редактирование задачи</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <input type="hidden" id="modalTaskId" name="task_id">

                    <div class="task-details">
                        <div class="detail-row">
                            <label>ID:</label>
                            <span id="modalTaskIdDisplay"></span>
                        </div>
                        <div class="detail-row">
                            <label>Пользователь:</label>
                            <span id="modalUsername"></span>
                        </div>
                        <div class="detail-row">
                            <label>Заголовок:</label>
                            <span id="modalTitle"></span>
                        </div>
                        <div class="detail-row">
                            <label>Описание:</label>
                            <div id="modalDescription" class="description-text"></div>
                        </div>
                        <div class="form-group">
                            <label>Теги:</label>
                            <div class="tags-container">
                                <div class="tags-checkbox-group">
                                    <label class="tag-checkbox">
                                        <input type="checkbox" name="tags[]" value="tech_issue"> Tech Issue
                                    </label>
                                    <label class="tag-checkbox">
                                        <input type="checkbox" name="tags[]" value="tech_question"> Tech Question
                                    </label>
                                    <label class="tag-checkbox">
                                        <input type="checkbox" name="tags[]" value="fatal_error"> Fatal Error
                                    </label>
                                    <label class="tag-checkbox">
                                        <input type="checkbox" name="tags[]" value="sales_question"> Sales Question
                                    </label>
                                    <label class="tag-checkbox">
                                        <input type="checkbox" name="tags[]" value="feature_request"> Feature Request
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="modalStatus">Статус:</label>
                            <select id="modalStatus" name="status" class="form-control">
                                <option value="todo">ToDo</option>
                                <option value="in_progress">In Progress</option>
                                <option value="ready_for_review">Ready For Review</option>
                                <option value="done">Done</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="modalAdminResponse">Ответ администратора:</label>
                            <textarea id="modalAdminResponse" name="admin_response" class="form-control" rows="4" placeholder="Введите ответ администратора..."></textarea>
                        </div>

                        <div class="detail-row">
                            <label>Дата создания:</label>
                            <span id="modalCreatedAt"></span>
                        </div>
                    </div>

                    <div id="editFormMessage" class="mt-2"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeTaskModal()" class="btn-secondary">Отмена</button>
                <button type="button" id="saveChangesBtn" class="btn-primary">Сохранить изменения</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentFilters = {
        status: '',
        sort: 'newest'
    };
    function openTaskModal(taskData) {
        $('#modalTaskId').val(taskData.id);
        $('#modalTaskIdDisplay').text(taskData.id);
        $('#modalUsername').text(taskData.username);
        $('#modalTitle').text(taskData.title);
        $('#modalDescription').text(taskData.description);

        // Устанавливаем статус
        $('#modalStatus').val(taskData.status || taskData.status_name || 'todo');

        $('#modalAdminResponse').val(taskData.admin_response || '');
        $('#modalCreatedAt').text(taskData.created_at);

        // Устанавливаем выбранные теги (теперь приходят слаги)
        $('input[name="tags[]"]').prop('checked', false);

        if (taskData.tags) {
            const tagsArray = taskData.tags.split(',');
            tagsArray.forEach(tagSlug => {
                const trimmedSlug = tagSlug.trim();
                $(`input[name="tags[]"][value="${trimmedSlug}"]`).prop('checked', true);
            });
        }

        $('#editFormMessage').empty();
        $('#viewTaskModal').removeClass('d-none');
    }

    function closeTaskModal() {
        $('#viewTaskModal').addClass('d-none');
    }

    function loadTableData() {
        $('#loadingSpinner').removeClass('d-none');
        $('#errorAlert').addClass('d-none');
        $('#emptyMessage').addClass('d-none');
        $('#tableBody').empty();

        $.ajax({
            url: '../../actions/task/index.php',
            type: 'GET',
            dataType: 'json',
            data: currentFilters,
            success: function(response) {
                $('#loadingSpinner').addClass('d-none');

                if (response.success) {
                    populateStatusFilter(response.statuses);

                    if (response.data && response.data.length > 0) {
                        renderTable(response.data);
                    } else {
                        $('#emptyMessage').removeClass('d-none');
                    }
                }
            },
            error: function(xhr, status, error) {
                $('#loadingSpinner').addClass('d-none');
                $('#errorAlert').removeClass('d-none').text('Ошибка загрузки данных');
                console.error('AJAX Error:', error);
            }
        });
    }

    function populateStatusFilter(statuses) {
        const statusFilter = $('#statusFilter');

        const currentValue = statusFilter.val();

        statusFilter.empty().append('<option value="">Все статусы</option>');

        if (statuses && Array.isArray(statuses)) {
            statuses.forEach(function(status) {
                const statusValue = status.value || status.id || status;
                const statusText = status.name || status.label || status;

                statusFilter.append(
                    $('<option></option>').val(statusValue).text(statusText)
                );
            });
        }

        if (currentValue) {
            statusFilter.val(currentValue);
        }
    }

    function getStatusText(status, statusName = null) {
        if (statusName) {
            return statusName;
        }

        const statusMap = {
            'in_progress': 'In Progress',
            'ready_for_review': 'Ready For Review',
            'todo': 'To Do',
            'done': 'Done'
        };
        return statusMap[status] || status;
    }

    function applyFilters() {
        currentFilters = {
            status: $('#statusFilter').val(),
            sort: $('#sortOrder').val()
        };
        loadTableData();
    }

    $('#applyFilters').click(applyFilters);

    $('#statusFilter, #sortOrder').change(function() {
        applyFilters();
    });
    function renderTable(data) {
        let tableBody = $('#tableBody');
        tableBody.empty();

        data.forEach(function(request, index) {
            let adminResponse = request.admin_response
                ? `<span class="text-success">${request.admin_response}</span>`
                : `<span class="text-muted font-italic">Ожидает ответа</span>`;

            let tagsHtml = '';
            if (request.tags) {
                const tagsArray = request.tags.split(',');
                tagsHtml = tagsArray.map(tagSlug => {
                    const tagName = convertTagSlugToName(tagSlug.trim());
                    return `<span class="tag-badge">${tagName}</span>`;
                }).join(' ');
            } else {
                tagsHtml = '<span class="text-muted font-italic">Нет тегов</span>';
            }

            let row = `
            <tr>
                <td>${index + 1}</td>
                <td>${request.username}</td>
                <td>
                    <span class="badge ${request.status_name}">
                        ${request.status_name || 'No Status'}
                    </span>
                </td>
                <td>${tagsHtml}</td>
                <td>${request.title}</td>
                <td class="description-cell">${request.description}</td>
                <td>${adminResponse}</td>
                <td>${request.created_at}<td>
                <td>
                    <button class="btn-view" data-task='${JSON.stringify(request)}'>
                        Просмотр
                    </button>
                </td>
            </tr>
        `;
            tableBody.append(row);
        });
    }

    function convertTagSlugToName(tagSlug) {
        const tagMap = {
            'tech_issue': 'Tech Issue',
            'tech_question': 'Tech Question',
            'fatal_error': 'Fatal Error',
            'sales_question': 'Sales Question',
            'feature_request': 'Feature Request'
        };

        return tagMap[tagSlug] || tagSlug;
    }

    $(document).ready(function() {
        loadTableData();

        $('#refreshBtn').click(function() {
            loadTableData();
        });

        $('#saveChangesBtn').click(function() {
            const selectedTags = [];
            $('input[name="tags[]"]:checked').each(function() {
                selectedTags.push($(this).val());
            });

            const formData = {
                task_id: $('#modalTaskId').val(),
                status: $('#modalStatus').val(),
                admin_response: $('#modalAdminResponse').val(),
                tags: selectedTags
            };

            $('#saveChangesBtn').prop('disabled', true).text('Сохранение...');

            $.ajax({
                url: '../../actions/task/update.php',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(response) {
                    $('#saveChangesBtn').prop('disabled', false).text('Сохранить изменения');

                    if (response.success) {
                        $('#editFormMessage').html('<div class="alert info-alert">Изменения сохранены успешно!</div>');
                        setTimeout(() => {
                            loadTableData();
                            closeTaskModal();
                        }, 1000);
                    } else {
                        let errorText = response.error || 'Ошибка при сохранении';
                        if (response.errors) {
                            errorText = Object.values(response.errors).join('<br>');
                        }
                        $('#editFormMessage').html('<div class="alert error-alert">' + errorText + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#saveChangesBtn').prop('disabled', false).text('Сохранить изменения');
                    $('#editFormMessage').html('<div class="alert error-alert">Ошибка при сохранении изменений</div>');
                    console.error('Update task error:', error);
                }
            });
        });

        $(document).on('click', '.btn-view', function() {
            const taskData = $(this).data('task');
            openTaskModal(taskData);
        });

        $('.close-modal, .btn-secondary').click(closeTaskModal);

        $('#viewTaskModal').click(function(e) {
            if (e.target === this) {
                closeTaskModal();
            }
        });

        $(document).keyup(function(e) {
            if (e.key === "Escape") {
                closeTaskModal();
            }
        });

        setInterval(loadTableData, 30000);
    });
</script>