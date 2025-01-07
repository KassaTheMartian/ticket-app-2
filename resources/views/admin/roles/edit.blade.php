@extends('layouts.app')

@section('page-title', "Update role")

@section('content')
    <div ng-app="role" ng-controller="roleController">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit role: {{ $role->name }}</h4>
                <div>
                    <span style="font-size: 0.8em;" class="badge bg-light text-dark me-2">Total Permissions: @{{roles.length}}</span>
                    <span style="font-size: 0.8em;" class="badge bg-success">Selected: @{{selectedRoles.length}}</span>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Role Name Input -->
                            <div class="mb-3">
                                <label for="roleName" class="form-label">Role Name</label>
                                <input type="text" name="name" id="roleName" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $role->name) }}" 
                                       placeholder="Enter role name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Search Route -->
                            <div class="mb-3">
                                <label for="searchRole" class="form-label">Search permissions</label>
                                <input type="text" id="searchRole" class="form-control" ng-model="rname" placeholder="Enter permissions">
                            </div>

                            <!-- Prefix Selection -->
                            <div class="mb-3">
                                <label class="form-label">Permissions Groups</label>
                                <div class="list-group">
                                    <button type="button" 
                                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                                            ng-repeat="(prefix, data) in prefixGroups" 
                                            ng-click="selectByPrefix(prefix)">
                                        @{{data.displayName}}
                                        <span class="badge bg-primary rounded-pill">@{{data.count}}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <!-- Checkbox Check All -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" id="checkAll" class="form-check-input" ng-model="checkAll" ng-change="toggleCheckAll()">
                                    <label class="form-check-label" for="checkAll">Select All</label>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-secondary me-2" ng-click="clearSelection()">Clear Selection</button>
                                    <button type="submit" class="btn btn-primary" ng-disabled="selectedRoles.length === 0">
                                        Update (@{{selectedRoles.length}} permissions)
                                    </button>
                                </div>
                            </div>
                            <!-- Route List -->
                            <div class="mb-3">
                                <label for="rolesList" class="form-label">Permissions List</label>
                                <div class="border rounded p-3" style="max-height: 700px; overflow-y: auto;">
                                    <div class="row">
                                        <div class="col-md-4" ng-repeat="r in filteredRoles = (roles | filter:rname)">
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input role-item" 
                                                    type="checkbox" 
                                                    name="route[]" 
                                                    id="route-@{{r}}" 
                                                    value="@{{r}}" 
                                                    ng-checked="isChecked(r)" 
                                                    ng-click="toggleCheck(r)"
                                                    ng-disabled="isAuthRole(r)">
                                                <label class="form-check-label" for="route-@{{r}}" title="@{{r}}">
                                                    @{{getFormattedName(r)}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div ng-if="filteredRoles.length === 0" class="alert alert-info mt-2">
                                    No matching routes found
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.0/angular.min.js"></script>
<script type="text/javascript">
        var app = angular.module('role', []);

        app.controller('roleController', function($scope) {

        var allRoles = @json($routes);
        var roles = allRoles.filter(function(role) {
            return !role.startsWith('admin.auth');
        });
        var permissions = @json($permissions ?? []);
        $scope.roles = roles;
        $scope.selectedRoles = permissions;
        $scope.checkAll = false;
        $scope.prefixGroups = {};

        // Format route name for display
        function formatRouteName(route) {
            // Remove 'admin.' prefix
            let name = route.replace(/^admin\./, '');
            // Split by dots
            let parts = name.split('.');
            // Capitalize each part and join with space
            return parts.map(part => {
                return part.charAt(0).toUpperCase() + part.slice(1);
            }).join(' ');
        }

        // Format prefix name for display
        function formatPrefixName(prefix) {
            let name = prefix.replace(/^admin\./, '');
            name = name.charAt(0).toUpperCase() + name.slice(1);
            return name.replace('.', ' ');
        }

        // Generate prefix groups
        $scope.roles.forEach(function(role) {
            var parts = role.split('.');
            var prefix = parts[0] + (parts[1] ? '.' + parts[1] : '');
            
            if (!$scope.prefixGroups[prefix]) {
                $scope.prefixGroups[prefix] = {
                    count: 1,
                    displayName: formatPrefixName(prefix)
                };
            } else {
                $scope.prefixGroups[prefix].count++;
            }
        });

        // Add formatted name to scope
        $scope.getFormattedName = formatRouteName;

        $scope.isChecked = function(role) {
            return $scope.selectedRoles.indexOf(role) !== -1;
        };

        $scope.toggleCheck = function(role) {
            if ($scope.isAuthRole(role)) {
                return;
            }
            var idx = $scope.selectedRoles.indexOf(role);
            if (idx === -1) {
                $scope.selectedRoles.push(role);
            } else {
                $scope.selectedRoles.splice(idx, 1);
            }

            $scope.checkAll = $scope.selectedRoles.length === $scope.roles.length;
        };

        $scope.toggleCheckAll = function() {
            if ($scope.checkAll) {
                $scope.selectedRoles = angular.copy($scope.roles);
            } else {
                $scope.selectedRoles = [];
            }
        };

        $scope.clearSelection = function() {
            $scope.selectedRoles = [];
            $scope.checkAll = false;
        };

        $scope.selectByPrefix = function(prefix) {
            var filteredRoles = $scope.roles.filter(function(role) {
                return role.startsWith(prefix);
            });

            var allSelected = filteredRoles.every(function(role) {
                return $scope.selectedRoles.indexOf(role) !== -1;
            });

            if (allSelected) {
                filteredRoles.forEach(function(role) {
                    var idx = $scope.selectedRoles.indexOf(role);
                    if (idx !== -1) {
                        $scope.selectedRoles.splice(idx, 1);
                    }
                });
            } else {
                filteredRoles.forEach(function(role) {
                    if ($scope.selectedRoles.indexOf(role) === -1) {
                        $scope.selectedRoles.push(role);
                    }
                });
            }

            $scope.checkAll = $scope.selectedRoles.length === $scope.roles.length;
        };
    });
</script>
@endsection
