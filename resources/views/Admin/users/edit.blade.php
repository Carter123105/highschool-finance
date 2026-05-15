@extends('layouts.app')
                           value="{{ $user->email }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>

                    <select name="role" class="form-select" required>

                        <option value="admin"
                            {{ $user->role == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="accountant"
                            {{ $user->role == 'accountant' ? 'selected' : '' }}>
                            Accountant
                        </option>

                        <option value="staff"
                            {{ $user->role == 'staff' ? 'selected' : '' }}>
                            Staff
                        </option>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        New Password (Optional)
                    </label>

                    <input type="password"
                           name="password"
                           class="form-control">
                </div>

                <button class="btn btn-warning px-4">
                    Update User
                </button>

            </form>

        </div>

    </div>

</div>

@endsection