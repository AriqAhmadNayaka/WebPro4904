import {
    BrowserRouter as Router,
    Routes,
    Route,
    Navigate,
} from 'react-router-dom';

import { AuthProvider } from './context/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';

import Login from './pages/Login';
import PostList from './pages/PostList';
import AllPosts from './pages/AllPosts';
import PostDetail from './pages/PostDetail';

import './index.css';

function App() {
    return (
        // AuthProvider dipasang di paling luar supaya status login bisa dipakai semua halaman.
        <AuthProvider>
            <Router>
                <Routes>

                    {/* Route Login */}
                    <Route
                        path="/login"
                        element={<Login />}
                    />

                    {/* Halaman di bawah ini hanya bisa dibuka setelah user login. */}
                    <Route
                        path="/posts"
                        element={
                            <ProtectedRoute>
                                <PostList />
                            </ProtectedRoute>
                        }
                    />

                    <Route
                        path="/all-posts"
                        element={
                            <ProtectedRoute>
                                <AllPosts />
                            </ProtectedRoute>
                        }
                    />

                    <Route
                        path="/posts/:id"
                        element={
                            <ProtectedRoute>
                                <PostDetail />
                            </ProtectedRoute>
                        }
                    />

                    {/* Redirect root ke posts */}
                    <Route
                        path="/"
                        element={
                            <Navigate
                                to="/posts"
                                replace
                            />
                        }
                    />

                    {/* 404 - Redirect ke posts */}
                    <Route
                        path="*"
                        element={
                            <Navigate
                                to="/posts"
                                replace
                            />
                        }
                    />

                </Routes>
            </Router>
        </AuthProvider>
    );
}

export default App;
