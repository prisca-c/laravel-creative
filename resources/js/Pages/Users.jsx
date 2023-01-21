import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/inertia-react';

export default function Users(props) {
    console.log(props)
    return (
        <AuthenticatedLayout
            auth={props.auth}
            admin={props.admin}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Users</h2>}
        >
            <Head title="Users" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                        <h2 className="text-2xl font-bold p-6 text-gray-900 text-center">
                            Users list
                        </h2>
                    </div>

                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                        <div className="grid grid-cols-2 gap-8">
                            {props.users.map((user)=>(
                                <div className="bg-white overflow-hidden sm:rounded-lg" key={user.id}>
                                    <p>
                                        <strong>ID:</strong> {user.id}
                                        <br/>
                                        <strong>Name:</strong> {user.name}
                                        <br/>
                                        <strong>Email:</strong> {user.email}
                                        <br/>
                                        <strong>Created At:</strong> {user.created_at.slice(0,16).split('T').join(' ')}
                                        <br/>
                                        <strong>Role:</strong> {user.is_admin ? "Admin" : "User"}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
