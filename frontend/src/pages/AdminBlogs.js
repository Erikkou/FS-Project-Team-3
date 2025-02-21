import React, { useState, useEffect } from "react";
import axios from "axios";

const Blogs = () => {
  const [blogs, setBlogs] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [isAdding, setIsAdding] = useState(false);
  const [newBlog, setNewBlog] = useState({
    title: "",
    author: "",
    description: "",
  });
  const [editBlog, setEditBlog] = useState(null);
  const [isEditing, setIsEditing] = useState(false);

  const API_BASE_URL = "http://localhost:9000"; // Symfony backend URL

  useEffect(() => {
    const fetchBlogs = async () => {
      try {
        const response = await axios.get(`${API_BASE_URL}/blogs`);
        setBlogs(response.data);
        setIsLoading(false);
      } catch (error) {
        console.error("Failed to fetch blogs:", error);
        setIsLoading(false);
      }
    };

    fetchBlogs();
  }, []);

  const handleRemove = async (id) => {
    if (window.confirm("Are you sure you want to delete this blog?")) {
      try {
        await axios.delete(`${API_BASE_URL}/blog/${id}`);
        setBlogs(blogs.filter((blog) => blog.id !== id));
      } catch (error) {
        console.error("Failed to delete blog:", error);
      }
    }
  };

  const handleAddNew = () => setIsAdding(true);

  const handleSaveNewBlog = async () => {
    if (!newBlog.title || !newBlog.author || !newBlog.description) {
      alert("All fields are required!");
      return;
    }

    try {
      const response = await axios.post(`${API_BASE_URL}/add/blog`, newBlog);
      setBlogs([...blogs, { ...newBlog, id: response.data.id }]);
      setIsAdding(false);
      setNewBlog({ title: "", author: "", description: "" });
    } catch (error) {
      console.error("Failed to add blog:", error);
    }
  };

  const handleEdit = (blog) => {
    setEditBlog(blog);
    setIsEditing(true);
  };

  const handleSaveEdit = async () => {
    try {
      await axios.put(`${API_BASE_URL}/blog/${editBlog.id}`, editBlog);
      setBlogs(blogs.map((blog) => (blog.id === editBlog.id ? editBlog : blog)));
      setIsEditing(false);
      setEditBlog(null);
    } catch (error) {
      console.error("Failed to update blog:", error);
    }
  };

  const handleCancelAdd = () => {
    setIsAdding(false);
    setNewBlog({ title: "", author: "", description: "" });
  };

  const handleCancelEdit = () => {
    setIsEditing(false);
    setEditBlog(null);
  };

  if (isLoading) {
    return <div>Loading...</div>;
  }

  return (
      <div className="min-h-screen bg-gray-800 text-white p-6">
        <h1 className="text-2xl font-bold mb-6">Blogs</h1>

        {!isAdding && !isEditing && (
            <>
              <button
                  onClick={handleAddNew}
                  className="bg-blue-600 hover:bg-blue-500 text-white py-2 px-4 rounded mb-6"
              >
                Add New Blog
              </button>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {blogs.map((blog) => (
                    <div
                        key={blog.id}
                        className="bg-gray-900 p-4 rounded-lg shadow-md"
                    >
                      <h3 className="text-xl font-bold">{blog.title}</h3>
                      <p>{blog.author}</p>
                      <p>{blog.description}</p>
                      <div className="mt-4 flex space-x-2">
                        <button
                            onClick={() => handleEdit(blog)}
                            className="bg-green-600 hover:bg-green-500 text-white py-1 px-3 rounded"
                        >
                          Edit
                        </button>
                        <button
                            onClick={() => handleRemove(blog.id)}
                            className="bg-red-600 hover:bg-red-500 text-white py-1 px-3 rounded"
                        >
                          Remove
                        </button>
                      </div>
                    </div>
                ))}
              </div>
            </>
        )}

        {isAdding && (
            <div className="bg-gray-900 p-6 rounded-lg shadow-md">
              <h2 className="text-xl font-bold mb-4">Add New Blog</h2>
              <input
                  type="text"
                  placeholder="Title"
                  value={newBlog.title}
                  onChange={(e) => setNewBlog({ ...newBlog, title: e.target.value })}
                  className="w-full p-2 mb-4 bg-gray-700 text-white rounded"
              />
              <input
                  type="text"
                  placeholder="Author"
                  value={newBlog.author}
                  onChange={(e) => setNewBlog({ ...newBlog, author: e.target.value })}
                  className="w-full p-2 mb-4 bg-gray-700 text-white rounded"
              />
              <textarea
                  placeholder="Description"
                  value={newBlog.description}
                  onChange={(e) =>
                      setNewBlog({ ...newBlog, description: e.target.value })
                  }
                  className="w-full p-2 mb-4 bg-gray-700 text-white rounded"
              ></textarea>
              <button
                  onClick={handleSaveNewBlog}
                  className="bg-green-600 hover:bg-green-500 text-white py-2 px-4 rounded"
              >
                Save
              </button>
              <button
                  onClick={handleCancelAdd}
                  className="ml-4 bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded"
              >
                Cancel
              </button>
            </div>
        )}

        {isEditing && (
            <div className="bg-gray-900 p-6 rounded-lg shadow-md">
              <h2 className="text-xl font-bold mb-4">Edit Blog</h2>
              <input
                  type="text"
                  value={editBlog.title}
                  onChange={(e) =>
                      setEditBlog({ ...editBlog, title: e.target.value })
                  }
                  className="w-full p-2 mb-4 bg-gray-700 text-white rounded"
              />
              <input
                  type="text"
                  value={editBlog.author}
                  onChange={(e) =>
                      setEditBlog({ ...editBlog, author: e.target.value })
                  }
                  className="w-full p-2 mb-4 bg-gray-700 text-white rounded"
              />
              <textarea
                  value={editBlog.description}
                  onChange={(e) =>
                      setEditBlog({ ...editBlog, description: e.target.value })
                  }
                  className="w-full p-2 mb-4 bg-gray-700 text-white rounded"
              ></textarea>
              <button
                  onClick={handleSaveEdit}
                  className="bg-green-600 hover:bg-green-500 text-white py-2 px-4 rounded"
              >
                Save
              </button>
              <button
                  onClick={handleCancelEdit}
                  className="ml-4 bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded"
              >
                Cancel
              </button>
            </div>
        )}
      </div>
  );
};

export default Blogs;
