import React from "react";
import { InertiaLink } from "@inertiajs/inertia-react";
import Modal from "@/Components/Modal";
import {Inertia} from "@inertiajs/inertia";

export const ArticleStoreForm = (props) => {
    const form = () => {
        const handleSubmit = (e) => {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const user = props.auth.user;
            data.user_id = user.id;
            console.log(data);
            axios.post(route('articles.store'), data).then((response) => {
                console.log(response);
                props.setUpdateCount(props.updateCount + 1);
                props.setShowForm(false);
            }).catch((error) => {
                console.log(error);
            });
        }
        return (
            <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form onSubmit={handleSubmit}>
                    <div className="mb-4">
                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="title">
                            Title
                        </label>
                        <input
                            className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="title"
                            type="text"
                            name="title"
                            required
                            autoFocus
                        />
                    </div>
                    <div className="mb-4">
                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="description">
                            Description
                        </label>
                        <textarea
                            className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="description"
                            name="description"
                            required
                        />
                    </div>
                    <div className="mb-4">
                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="content">
                            Content
                        </label>
                        <textarea
                            className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="content"
                            name="content"
                            required
                        />
                    </div>
                    <div className="mb-4">
                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="published_at">
                            Published At
                        </label>
                        <input
                            className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="published_at"
                            type="datetime-local"
                            name="published_at"
                            defaultValue={new Date().toISOString().slice(0, 16)}
                            required={true}
                        />
                    </div>

                    <div className="mb-4">
                        <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="tags">
                            Tags
                        </label>
                        <select
                            className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="tags"
                            name="tags"
                            >
                            {props.tags.map((tag) => (
                                <option value={tag.id} key={tag.id}>{tag.name}</option>
                            ))}
                        </select>
                    </div>

                    <div className="flex items-center justify-center gap-5">
                        <button
                            onClick={() => props.setShowForm(false)}
                            className="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
                        >
                            Cancel
                        </button>
                        <button
                            className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit"
                        >
                            Create
                        </button>
                    </div>
                </form>
            </div>
        )
    }
    return (
        <Modal children={form()} closeable={true} show={props.showForm} />
    );
}
