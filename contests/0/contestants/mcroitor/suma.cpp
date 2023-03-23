#include <iostream>

int main() {
	int n, sum = 0;
	int v;

	std::cin >> n;
	v.resize(n);
	for(int i = 0; i < n; ++i) {
		std::cin >> v;
		sum += v;
	}
	std::cout << sum;
	return 0;
}
