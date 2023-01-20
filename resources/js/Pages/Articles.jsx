import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/inertia-react';
import {useEffect, useState} from "react";
import {ArticleStoreForm} from "@/Components/ArticleStoreForm";
import {ArticleEditForm} from "@/Components/ArticleEditForm";

export default function Dashboard(props) {
    const [showForm, setShowForm] = useState(false);
    const [showEditForm, setShowEditForm] = useState(false);
    const [updateCount, setUpdateCount] = useState(0);
    const [articles, setArticles] = useState([]);
    const [editedArticle, setEditedArticle] = useState({});
    const [filter, setFilter] = useState('all');

    useEffect(() => {
        if(props.auth.user.is_admin === 1) {
            if(filter === 'all') {
                axios.get(route('articles.index')).then((response) => {
                    setArticles(response.data);
                });
            } else {
                axios.get(route('articles.tag', filter)).then((response) => {
                    setArticles(response.data);
                });
            }
        } else {
            axios.get(route('articles.user')).then((response) => {
                setArticles(response.data);
                console.log(response.data);
            });
        }
    }, [updateCount]);

    const deleteArticle = (e) => {
        axios.delete(route('articles.destroy', e.target.value)).then((response) => {
            setUpdateCount(updateCount + 1);
        });
    }

    const handleFilter = (e) => {
        setFilter(e.target.value);
        setUpdateCount(updateCount + 1);
    }

    const resetFilter = () => {
        setFilter('all');
        setUpdateCount(updateCount + 1);
    }

    const editArticle = (e) => {
        setEditedArticle(articles.find(article => article.id === e.target.value));
        setShowEditForm(true);
    }

    const articlesView = () => {
        if(articles.length === 0) {
            return (
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p className="text-sm text-gray-500">No articles</p>
                </div>
            );
        } else {
            return (
                articles.map((article) => (
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6" key={article.id}>
                        <div className=" text-gray-900">
                            <h2 className="text-2xl font-bold">{article.title}</h2>
                            <p className="text-sm text-gray-500">Publish date: {article.published_at}</p>
                            <p className="text-sm text-gray-500">Create date : {article.created_at}</p>
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
                        <div className="flex items-center justify-end gap-5 mt-5">
                            <button
                                className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                onClick={editArticle}
                                value={article.id}
                            >
                                Edit
                            </button>
                            <button
                                className="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onClick={deleteArticle}
                                value={article.id}
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                ))
            )
        }
    }

    return (
        <AuthenticatedLayout
            auth={props.auth}
            admin={props.admin}
            errors={props.errors}
            header={
            <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                {props.auth.user.is_admin === 1 ? 'Articles' : 'My Article'}
            </h2>}
        >
            <Head title={props.auth.user.is_admin === 1 ? 'Articles' : 'My Article'} />

            <ArticleStoreForm
                showForm={showForm}
                tags={props.tags}
                auth={props.auth}
                setUpdateCount={setUpdateCount}
                updateCount={updateCount}
                setShowForm={setShowForm}/>

            <ArticleEditForm
                showEditForm={showEditForm}
                tags={props.tags}
                auth={props.auth}
                setUpdateCount={setUpdateCount}
                updateCount={updateCount}
                setShowEditForm={setShowEditForm}
                article={editedArticle}/>

            <div className="fixed bottom-0 right-0 m-5">
                <button
                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    onClick={() => setShowForm(true)}>
                    Create Article
                </button>
            </div>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <p className="text-sm text-gray-900">Filter</p>
                    <div className="flex items-center gap-5">
                        <select
                            className="bg-white border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            onChange={handleFilter}
                        >
                            <option value="all">All</option>
                            {props.tags.map((tag) => (
                                <option key={tag.id} value={tag.id}>{tag.name}</option>
                            ))}
                        </select>
                        <button
                            className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            onClick={resetFilter}>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <div className="pt-3 pb-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                        <h2 className="text-2xl font-bold p-6 text-gray-900 text-center">
                            {props.auth.user.is_admin === 1 ? 'Latest Created Articles' : 'My Articles'}
                        </h2>
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                        {articlesView()}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
