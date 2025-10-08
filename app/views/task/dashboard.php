<?php
$title = "Панель управления";
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
                            <th>Заголовок</th>
                            <th>Описание</th>
                            <th>Ответ администратора</th>
                            <th>Дата создания</th>
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

    <!-- Правая часть - форма создания задачи -->
    <div class="create-task-section">
        <h3>Создать новую задачу</h3>
        <form id="createTaskForm">
            <div class="form-group">
                <label for="title">Заголовок</label>
                <input type="text" id="title" name="title" class="form-control">
            </div>
            <div class="form-group">
                <label for="description">Описание</label>
                <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn-primary">Создать задачу</button>
        </form>
        <div id="formMessage" class="mt-2"></div>
    </div>
</div>

<!-- Подключаем jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let currentFilters = {
            status: '',
            sort: 'newest'
        };

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

        function renderTable(data) {
            let tableBody = $('#tableBody');
            tableBody.empty();

            data.forEach(function(request, index) {
                let adminResponse = request.admin_response
                    ? `<span class="text-success">${request.admin_response}</span>`
                    : `<span class="text-muted font-italic">Ожидает ответа</span>`;

                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${request.username}</td>
                        <td>
                            <span class="badge ${request.status}">
                                ${getStatusText(request.status, request.status_name)}
                            </span>
                        </td>
                        <td>${request.title}</td>
                        <td>${request.description}</td>
                        <td>${adminResponse}</td>
                        <td>${request.created_at} <td>
                    </tr>
                `;
                tableBody.append(row);
            });
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

        // Остальной код формы создания задачи
        $('#createTaskForm').submit(function(e) {
            e.preventDefault();

            const formData = {
                title: $('#title').val(),
                description: $('#description').val()
            };

            $.ajax({
                url: '../../actions/task/store.php',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(response) {
                    $('.field-error').remove();
                    $('#formMessage').empty();
                    if (response.success) {
                        $('#formMessage').html('<div class="alert info-alert">Задача успешно создана!</div>');
                        $('#createTaskForm')[0].reset();
                        loadTableData()
                    } else {
                        if (response.errors) {
                            for (let field in response.errors) {
                                if (response.errors.hasOwnProperty(field)) {
                                    $('#' + field).after('<div class="field-error text-danger">' + response.errors[field] + '</div>');
                                }
                            }
                        } else if (response.error) {
                            $('#formMessage').html('<div class="alert error-alert">' + response.error + '</div>');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    $('.field-error').remove();
                    $('#formMessage').empty();
                    $('#formMessage').html('<div class="alert error-alert">Ошибка при создании задачи</div>');
                    console.error('Create task error:', error);
                }
            });
        });

        loadTableData();

        $('#refreshBtn').click(function() {
            loadTableData();
        });

        setInterval(loadTableData, 30000);
    });
</script>