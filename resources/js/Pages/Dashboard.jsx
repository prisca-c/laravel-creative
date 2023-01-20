import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/inertia-react';
import {useState} from "react";
import {ArticleStoreForm} from "@/Components/ArticleStoreForm";

export default function Dashboard(props) {
    const [showForm, setShowForm] = useState(false);
    const [updateCount, setUpdateCount] = useState(0);

    return (
        <AuthenticatedLayout
            auth={props.auth}
            admin={props.admin}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <ArticleStoreForm
                showForm={showForm}
                tags={props.tags}
                auth={props.auth}
                setUpdateCount={setUpdateCount}
                updateCount={updateCount}
                setShowForm={setShowForm}/>

            <div className="fixed bottom-0 right-0 m-5">
                <button
                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    onClick={() => setShowForm(true)}>
                    Create Article
                </button>
            </div>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                        <h2 className="text-2xl font-bold p-6 text-gray-900 text-center">Latest Published Articles</h2>
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                        {props.articles.map((article) => (
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6" key={article.id}>
                            <div className=" text-gray-900">
                                <h2 className="text-2xl font-bold">{article.title}</h2>
                                <p className="text-sm text-gray-500">Published : {article.published_at}</p>
                                <p className="text-sm text-gray-500">Author : {article.user['name']}</p>
                                <p className="text-sm text-gray-500">Tags :{
                                    article.tags.map((tag) => (
                                        <span className="text-sm text-gray-500" key={tag.id}> #{tag.name} </span>
                                    ))
                                }</p>
                            </div>
                            <div className="text-gray-900">
                                <p className="text-sm text-gray-500">Description : {article.description}</p>
                                <p className="text-sm text-gray-500">Content : {article.content}</p>
                            </div>
                        </div>
                        ))}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
