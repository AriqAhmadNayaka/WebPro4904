export const fetchProducts = async () => {
    return new Promise((resolve) => {

        // Simulasi jeda request API agar pengambilan data terasa seperti dari server.
        setTimeout(() => {
            resolve([
                {
                    id: 1,
                    title: "Fastkin Pure Valor Cosmic Purple",
                    price: 276,
                    rating: { rate: 4.8, count: 230 },

                    image: "https://indoswimgear.com/wp-content/uploads/2024/03/230418125557459.jpg"
                },
                {
                    id: 2,
                    title: "Adult Fastskin Hyper Elite Mirrored Goggles Blue/Purple",
                    price: 67,
                    rating: { rate: 4.6, count: 150 },

                    image: "https://static.thcdn.com/images/v2/productimg/original/17469689-1665303333260947.jpg?width=960&height=960"
                },
                {
                    id: 3,
                    title: "Speedo DMC Elite Max Fin Purple",
                    price: 82,
                    rating: { rate: 4.9, count: 85 },

                    image: "https://static.thcdn.com/images/v2/productimg/original/15820512-1435218569355452.jpg?width=310&height=310&isWebP=true"
                },

                {
                    id: 4,
                    title: "Adult Fastskin Cap Lilac",
                    price: 41,
                    rating: { rate: 4.7, count: 500 },

                    image: "https://static.thcdn.com/productimg/300/300/14206313-7305019063695097.jpg"
                },
                {
                    id: 5,
                    title: "45L Pro Rucksack Black",
                    price: 76,
                    rating: { rate: 4.5, count: 120 },

                    image: "https://static.thcdn.com/images/v2/productimg/original/17471710-1625303885456856.jpg?width=310&height=310&isWebP=true"
                },
                {
                    id: 6,
                    title: "Printed Bullet Head Snorkel Blue/Green",
                    price: 54,
                    rating: { rate: 4.8, count: 320 },

                    image: "https://static.thcdn.com/images/v2/productimg/original/14231319-1695256725005158.jpg?width=310&height=310&isWebP=true"
                },
            ]);
        }, 800);
    });
};
