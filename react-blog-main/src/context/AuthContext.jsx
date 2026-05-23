import { createContext, useContext, useState, useEffect } from 'react'; //untuk membuat konteks, mengakses konteks, dan mengelola state 
// serta efek samping dalam komponen React
import { authAPI } from '../services/api'; //untuk mengimpor fungsi-fungsi yang berhubungan dengan autentikasi dari file api.js, 
// seperti login dan logout, yang akan digunakan dalam konteks ini untuk mengelola status autentikasi pengguna.

const AuthContext = createContext(null); //untuk membuat konteks autentikasi yang akan digunakan untuk menyediakan informasi tentang status 
// autentikasi pengguna dan fungsi-fungsi terkait ke seluruh aplikasi React.

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(localStorage.getItem('token'));
    const [loading, setLoading] = useState(true);

    useEffect(() => { //untuk memeriksa status autentikasi pengguna saat komponen AuthProvider pertama kali dimuat. Fungsi checkAuth 
    // akan mencoba mengambil token dan data pengguna yang disimpan di localStorage. Jika ditemukan, token dan data pengguna 
    // akan disimpan dalam state. Setelah pemeriksaan selesai, state loading akan diatur ke false untuk menunjukkan bahwa proses pemeriksaan telah selesai.
        const checkAuth = async () => {
            const savedToken = localStorage.getItem('token');
            const savedUser = localStorage.getItem('user');

            if (savedToken && savedUser) {
                setToken(savedToken);
                setUser(JSON.parse(savedUser));
            }
            setLoading(false);
        };

        checkAuth(); //untuk memeriksa status autentikasi pengguna saat komponen AuthProvider pertama kali dimuat.
        //  Fungsi checkAuth akan mencoba mengambil token dan data pengguna yang disimpan di localStorage. Jika ditemukan, 
        // token dan data pengguna akan disimpan dalam state. Setelah pemeriksaan selesai, state loading akan diatur
        //  ke false untuk menunjukkan bahwa proses pemeriksaan telah selesai.
    }, []);

    const login = async (email, password) => {
        try {
            const response = await authAPI.login(email, password);
            const { access_token, data: userData } = response.data;

            localStorage.setItem('token', access_token);
            localStorage.setItem('user', JSON.stringify(userData));

            setToken(access_token);
            setUser(userData);

            return { success: true };
        } catch (error) {
            const message = error.response?.data?.message || 'Login gagal';
            return { success: false, message };
        }
    };

    const logout = async () => { //untuk mengelola proses logout pengguna.
    //  Fungsi logout akan mencoba memanggil API logout untuk memberitahu server
    //  bahwa pengguna ingin keluar. Setelah itu, token dan data pengguna akan dihapus
    //  dari localStorage, dan state token serta user akan diatur ke null untuk mencerminkan 
    // bahwa pengguna telah keluar dari aplikasi.
        try {
            await authAPI.logout();
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            setToken(null);
            setUser(null);
        }
    };

    const value = {
        user,
        token,
        loading,
        isAuthenticated: !!token,
        login,
        logout,
    };

    return (
        <AuthContext.Provider value={value}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => { //untuk membuat custom hook useAuth yang memungkinkan
//  komponen lain untuk dengan mudah mengakses informasi autentikasi dan fungsi-fungsi terkait
//  dari konteks AuthContext. Hook ini akan memeriksa apakah konteks tersedia dan melemparkan
//  error jika digunakan di luar AuthProvider, memastikan bahwa hanya komponen yang berada dalam AuthProvider yang dapat mengakses informasi autentikasi.
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth harus digunakan di dalam AuthProvider');
    }
    return context; //untuk membuat custom hook useAuth yang memungkinkan komponen lain 
    // untuk dengan mudah mengakses informasi autentikasi dan fungsi-fungsi terkait dari
    //  konteks AuthContext. Hook ini akan memeriksa apakah konteks tersedia dan melemparkan 
    // error jika digunakan di luar AuthProvider, memastikan bahwa hanya komponen yang berada
    //  dalam AuthProvider yang dapat mengakses informasi autentikasi.
};
