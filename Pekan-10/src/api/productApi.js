export const fetchProducts = async () => { 
    return new Promise((resolve) => { 
 
        setTimeout(() => { 
            resolve([ 
                {  
                    id: 1,  
                    title: "Mechanical Keyboard",  
                    price: 150,  
                    rating: { rate: 4.8, count: 230 }, 
                    image: "https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?auto=format&fit=crop&w=800&q=80&utm_source=chatgpt.com",
                }, 
                {  
                    id: 2,  
                    title: "Gaming Mouse",  
                    price: 80,  
                    rating: { rate: 4.6, count: 150 }, 
                    image: "https://images.unsplash.com/photo-1527814050087-3793815479db?auto=format&fit=crop&w=800&q=80",
                }, 
                {  
                    id: 3,  
                    title: "4K Monitor 24\"",  
                    price: 300,  
                    rating: { rate: 4.9, count: 85 }, 
                    image: "https://images.unsplash.com/photo-1527443154391-507e9dc6c5cc?auto=format&fit=crop&w=800&q=80",  
                }, 
 
 
                {  
                    id: 4,  
                    title: "Premium Coffee Beans",  
                    price: 25,  
                    rating: { rate: 4.7, count: 500 }, 
                    image: "https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=800&q=80",  
                }, 
                {  
                    id: 5,  
                    title: "Travel Tumbler",  
                    price: 35,  
                    rating: { rate: 4.5, count: 120 }, 
                    image: "https://images.unsplash.com/photo-1578500494198-246f612d3b3d?auto=format&fit=crop&w=800&q=80",  
                }, 
                 {  
                    id: 6,  
                    title: "Noise Cancelling Headphones",  
                    price: 200,  
                    rating: { rate: 4.8, count: 320 }, 
                    image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80",  
                }, 
            ]); 
        }, 800); 
    }); 
}; 