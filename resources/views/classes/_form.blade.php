<div class="mb-3">
    <label class="form-label">Class Name</label>

    <input type="text"
           name="name"
           class="form-control"
           value="{{ old('name', $class->name ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Description</label>

    <textarea name="description"
              class="form-control"
              rows="4">{{ old('description', $class->description ?? '') }}</textarea>
</div>

<button class="btn btn-primary btn-loading">
    Save Class
</button>