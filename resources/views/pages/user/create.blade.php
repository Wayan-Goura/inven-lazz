<div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="name" class="form-control" placeholder="Nama User" required>
                    </div>
                    <div class="col-md-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-md-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Simpan User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>